<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Variant extends Model
{
    /** @use HasFactory<\Database\Factories\VariantFactory> */
    use HasFactory;

    protected $fillable = [
        'product_id',
        'sku',
        'barcode',
        'price',
        'sale_price',
        'stock',
        'is_default',
        'is_active'
    ];

    protected $appends = [
        'thumbnail_url',
        'final_price',
    ];

    // ... relationships ...

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    public function variantAttributeValue(): HasOne
    {
        return $this->hasOne(VariantAttributeValue::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductVariantImage::class);
    }

    public function thumbnail()
    {
        return $this->hasOne(ProductVariantImage::class)
            ->orderBy('is_primary', 'desc')
            ->orderBy('sort_order');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'variant_id');
    }

    // ... Domain business ...

    public function isAvailable(): bool
    {
        return $this->stock > 0 && $this->is_active;
    }

    public function hasDiscount(): bool
    {
        return $this->sale_price && $this->sale_price < $this->price;
    }

    // ... Attribute ...

    public function getThumbnailUrlAttribute(): string
    {
        return $this->thumbnail
            ? asset('storage/' . $this->thumbnail->image)
            : asset('images/no-image.jpg');
    }

    public function getFinalPriceAttribute()
    {
        return $this->sale_price ?? $this->price;
    }

    public function getVariantValueAttribute(): ?string
    {
        return $this->variantAttributeValue?->productAttributeValue?->value;
    }

    public function getVariantLabelAttribute()
    {
        return $this->variantAttributeValue
            ?->productAttributeValue
            ?->value;
    }

    public function discountPercent(): int
    {
        if (! $this->hasDiscount() || $this->price <= 0) {
            return 0;
        }

        return round((($this->price - $this->sale_price) / $this->price) * 100);
    }
}
