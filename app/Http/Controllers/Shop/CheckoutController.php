<?php
namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Mail\OrderPlaced;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    /** GET /checkout */
    public function index()
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('cart_error', 'Your cart is empty.');
        }
        return view('shop.checkout', compact('cart'));
    }

    /** POST /checkout */
    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:150',
            'email'   => 'required|email|max:150',
            'phone'   => 'required|string|max:30',
            'address' => 'required|string|max:400',
            'city'    => 'required|string|max:100',
            'payment_method' => 'required|in:cash_on_delivery,bank_transfer',
        ]);

        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('cart_error', 'Your cart is empty.');
        }

        // ── Prevent duplicate order on refresh ──────────────────────────
        $fingerprint = md5(json_encode($cart) . $request->email . $request->address);
        if (session('last_order_fingerprint') === $fingerprint) {
            $orderId = session('last_order_id');
            if ($orderId && ($existing = Order::find($orderId))) {
                return redirect()->route('order.confirmation', $existing->order_number);
            }
        }

        // Currency the customer is looking at + where their IP says they are (for fraud checks)
        $currency = \App\Support\Currency::current();
        $ip       = (string) $request->ip();
        try {
            $geo = \App\Support\Currency::ipDetails($ip);
        } catch (\Throwable $e) {
            $geo = ['country_code' => null, 'country' => null, 'region' => null, 'city' => null];
        }

        try {
            $order = DB::transaction(function () use ($request, $cart, $currency, $ip, $geo) {

                $subtotal    = 0;
                $lineItems   = [];

                // ── Validate stock and reload prices from DB ─────────────
                foreach ($cart as $key => $item) {
                    $product = Product::lockForUpdate()->find($item['product_id']);

                    if (!$product || $product->status !== 'active') {
                        throw new \Exception("\"{$item['name']}\" is no longer available.");
                    }
                    if ($product->quantity < $item['quantity']) {
                        throw new \Exception("Only {$product->quantity} unit(s) of \"{$product->name}\" are in stock.");
                    }

                    $unitPrice = (float) ($product->sale_price ?? $product->purchase_price);
                    $lineSub   = $unitPrice * $item['quantity'];
                    $subtotal += $lineSub;

                    $lineItems[] = [
                        'product_id'   => $product->id,
                        'product_name' => $product->name,
                        'product_sku'  => $product->sku,
                        'unit_price'   => $unitPrice,
                        'quantity'     => $item['quantity'],
                        'subtotal'     => $lineSub,
                    ];

                    // Decrement stock
                    $product->decrement('quantity', $item['quantity']);
                }

                $shippingFee = 0; // configurable; 0 for now
                $total       = $subtotal + $shippingFee;

                // ── Find or create customer ──────────────────────────────
                $customer = Customer::firstOrCreate(
                    ['email' => $request->email],
                    [
                        'name'    => $request->name,
                        'phone'   => $request->phone,
                        'address' => $request->address,
                        'city'    => $request->city,
                        'status'  => 'active',
                    ]
                );

                // ── Create the order ─────────────────────────────────────
                $order = Order::create([
                    'order_number'     => 'ORD-' . strtoupper(Str::random(8)),
                    'customer_id'      => $customer->id,
                    'customer_email'   => $request->email,
                    'status'           => 'pending',
                    'payment_status'   => 'unpaid',
                    'payment_method'   => $request->payment_method,
                    'subtotal'         => $subtotal,
                    'discount'         => 0,
                    'shipping_fee'     => $shippingFee,
                    'total'            => $total,
                    'shipping_name'    => $request->name,
                    'shipping_phone'   => $request->phone,
                    'shipping_address' => $request->address,
                    'shipping_city'    => $request->city,
                    'notes'            => $request->notes,
                    // snapshot of the currency shown at checkout (amounts above are in the base currency)
                    'currency_code'    => $currency['code'],
                    'currency_symbol'  => $currency['symbol'],
                    'exchange_rate'    => $currency['rate'],
                    // IP-derived location
                    'ip_address'       => $ip ?: null,
                    'ip_country_code'  => $geo['country_code'] ?? null,
                    'ip_country'       => $geo['country'] ?? null,
                    'ip_region'        => $geo['region'] ?? null,
                    'ip_city'          => $geo['city'] ?? null,
                ]);

                $order->items()->createMany($lineItems);

                return $order;
            });

            // ── Success ──────────────────────────────────────────────────
            session()->forget('cart');
            session(['last_order_fingerprint' => $fingerprint, 'last_order_id' => $order->id]);

            $this->sendOrderEmails($order);

            return redirect()->route('order.confirmation', $order->order_number);

        } catch (\Exception $e) {
            return back()->withInput()->with('checkout_error', $e->getMessage());
        }
    }

    /** GET /order-confirmation/{orderNumber} */
    public function confirmation(string $orderNumber)
    {
        $order = $this->findSessionOrder($orderNumber);
        return view('shop.confirmation', compact('order'));
    }

    /** GET /order/{orderNumber}/invoice — PDF when dompdf is installed, printable HTML otherwise */
    public function invoice(string $orderNumber)
    {
        $order = $this->findSessionOrder($orderNumber);
        $store = $this->storeDetails();

        if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('shop.invoice', ['order' => $order, 'pdf' => true, 'store' => $store])
                ->setPaper('a4');
            return $pdf->download('Zelari-Invoice-' . $order->order_number . '.pdf');
        }

        return view('shop.invoice', ['order' => $order, 'pdf' => false, 'store' => $store]);
    }

    // ── Helpers ──────────────────────────────────────────────────────────

    /** Only the customer who placed the order (this session) or a logged-in admin may view it. */
    private function findSessionOrder(string $orderNumber): Order
    {
        $order = Order::with(['items.product', 'customer'])->where('order_number', $orderNumber)->firstOrFail();
        $isOwner = (int) session('last_order_id') === (int) $order->id;
        if (!$isOwner && !auth()->check()) {
            abort(403);
        }
        return $order;
    }

    private function storeDetails(): array
    {
        return [
            'name'    => Setting::get('store_name', 'Zelari'),
            'phone'   => Setting::get('store_phone', ''),
            'address' => Setting::get('store_address', ''),
            'email'   => Setting::get('store_email', Setting::get('mail_from_address', '')),
        ];
    }

    /** Email the customer (and optionally the admin). Never breaks checkout. */
    private function sendOrderEmails(Order $order): void
    {
        try {
            if (!Setting::applyMailConfig()) {
                return;
            }
            $order->loadMissing('items.product', 'customer');

            if ($order->customer_email) {
                Mail::to($order->customer_email, $order->shipping_name)->send(new OrderPlaced($order));
            }
            if ($admin = Setting::get('mail_admin_address')) {
                Mail::to($admin)->send(new OrderPlaced($order, true));
            }
            Setting::recordMailResult(null);
        } catch (\Throwable $e) {
            report($e);
            Setting::recordMailResult('Order #' . $order->order_number . ': ' . $e->getMessage());
        }
    }
}
