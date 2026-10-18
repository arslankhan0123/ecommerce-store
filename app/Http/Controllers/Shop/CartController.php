<?php
namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /** GET /cart */
    public function index()
    {
        $cart = session('cart', []);
        return view('shop.cart', compact('cart'));
    }

    /** POST /cart/add */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quantity'   => 'required|integer|min:1|max:100',
        ]);

        $product = Product::where('id', $request->product_id)
            ->where('status', 'active')
            ->firstOrFail();

        $qty = (int) $request->quantity;

        if ($qty > $product->quantity) {
            $msg = "Sorry, only {$product->quantity} unit(s) available.";
            if ($request->wantsJson()) {
                return response()->json(['message' => $msg], 422);
            }
            return back()->with('cart_error', $msg);
        }

        $cart = session('cart', []);
        $key  = (string) $product->id;

        if (isset($cart[$key])) {
            $newQty = $cart[$key]['quantity'] + $qty;
            if ($newQty > $product->quantity) {
                $newQty = $product->quantity;
            }
            $cart[$key]['quantity'] = $newQty;
        } else {
            $cart[$key] = [
                'product_id'   => $product->id,
                'name'         => $product->name,
                'slug'         => $product->slug,
                'price'        => (float) ($product->sale_price ?? $product->purchase_price),
                'image'        => $product->feature_image,
                'quantity'     => $qty,
                'max_quantity' => $product->quantity,
            ];
        }

        session(['cart' => $cart]);

        if ($request->wantsJson()) {
            return response()->json([
                'count'   => $this->cartCount($cart),
                'total'   => array_sum(array_map(fn ($i) => $i['price'] * $i['quantity'], $cart)),
                'name'    => $product->name,
                'message' => 'Added to cart!',
            ]);
        }

        return back()->with('cart_success', "\"{$product->name}\" added to your cart.");
    }

    /** POST /cart/update */
    public function update(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quantity'   => 'required|integer|min:0|max:100',
        ]);

        $cart = session('cart', []);
        $key  = (string) $request->product_id;

        if (isset($cart[$key])) {
            if ((int) $request->quantity === 0) {
                unset($cart[$key]);
            } else {
                // Re-check stock
                $stock = Product::where('id', $request->product_id)->value('quantity');
                $cart[$key]['quantity'] = min((int) $request->quantity, $stock ?? 1);
            }
            session(['cart' => $cart]);
        }

        return redirect()->route('cart.index');
    }

    /** DELETE /cart/remove/{productId} */
    public function remove(int $productId)
    {
        $cart = session('cart', []);
        unset($cart[(string) $productId]);
        session(['cart' => $cart]);

        return redirect()->route('cart.index')->with('cart_success', 'Item removed from cart.');
    }

    /** DELETE /cart/clear */
    public function clear()
    {
        session()->forget('cart');
        return redirect()->route('cart.index');
    }

    // ── Helper ────────────────────────────────────────────────────────────

    private function cartCount(array $cart): int
    {
        return array_sum(array_column($cart, 'quantity'));
    }
}
