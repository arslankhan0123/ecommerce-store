<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'category_id', 'name', 'slug', 'sku',
        'feature_image', 'purchase_price', 'sale_price',
        'quantity', 'short_description', 'description', 'status',
    ];

    protected $casts = [
        'purchase_price' => 'decimal:2',
        'sale_price'     => 'decimal:2',
        'quantity'       => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($p) {
            if (empty($p->slug)) {
                $p->slug = Str::slug($p->name);
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function getDiscountPercentAttribute(): ?int
    {
        if ($this->sale_price && $this->purchase_price > 0) {
            return (int) round((($this->purchase_price - $this->sale_price) / $this->purchase_price) * 100);
        }
        return null;
    }

    public function getEffectivePriceAttribute(): string
    {
        return number_format($this->sale_price ?? $this->purchase_price, 2);
    }
}
