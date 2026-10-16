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
    ];

    protected $casts = [
        'subtotal'     => 'decimal:2',
        'discount'     => 'decimal:2',
        'shipping_fee' => 'decimal:2',
        'total'        => 'decimal:2',
    ];

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
