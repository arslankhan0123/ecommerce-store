<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model {
    protected $fillable = [
        'order_number','customer_id','customer_email','status','payment_status','payment_method',
        'subtotal','discount','shipping_fee','total',
        'shipping_name','shipping_phone','shipping_address','shipping_city','notes',
        'currency_code','currency_symbol','exchange_rate',
        'ip_address','ip_country_code','ip_country','ip_region','ip_city',
    ];

    protected $casts = [
        'subtotal'      => 'decimal:2',
        'discount'      => 'decimal:2',
        'shipping_fee'  => 'decimal:2',
        'total'         => 'decimal:2',
        'exchange_rate' => 'float',
    ];

    /** Format a base-currency amount in the currency this order was placed in. */
    public function money($amount): string
    {
        return zorder_price($this, $amount);
    }

    /** "Lahore, Punjab, Pakistan" from the IP lookup, or null when nothing was captured. */
    public function getIpLocationAttribute(): ?string
    {
        $parts = array_filter([$this->ip_city, $this->ip_region, $this->ip_country]);
        return $parts ? implode(', ', array_unique($parts)) : null;
    }

    /** Does the IP country match the country that uses the order's shipping city/currency? Null = unknown. */
    public function getLocationMatchesAttribute(): ?bool
    {
        if (!$this->ip_country_code) return null;
        $city = mb_strtolower(trim((string) $this->shipping_city));
        if ($city !== '' && $this->ip_city && mb_strtolower($this->ip_city) === $city) return true;
        $countries = config('currencies.countries', []);
        $orderCountry = null;
        foreach ($countries as $cc => $c) {
            if (($c['currency'] ?? null) === $this->currency_code) { $orderCountry = $cc; break; }
        }
        return $orderCountry ? $orderCountry === $this->ip_country_code : null;
    }

    public function customer(): BelongsTo {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany {
        return $this->hasMany(OrderItem::class);
    }

    public function getStatusColorAttribute(): string {
        return match($this->status) {
            'pending'    => 'yellow',
            'processing' => 'blue',
            'shipped'    => 'indigo',
            'delivered'  => 'green',
            'cancelled'  => 'red',
            default      => 'gray',
        };
    }

    public function getPaymentStatusColorAttribute(): string {
        return match($this->payment_status) {
            'paid'     => 'green',
            'unpaid'   => 'red',
            'refunded' => 'yellow',
            default    => 'gray',
        };
    }
}
