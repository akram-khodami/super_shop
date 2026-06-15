<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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

    public function product()
    {
        return $this->belongsTo(
            Product::class
        );
    }

    public function stockMovements()
    {
        return $this->hasMany(
            StockMovement::class
        );
    }

    public function variantAttributeValue(): HasOne
    {
        return $this->hasOne(VariantAttributeValue::class);
    }

    public function getVariantValueAttribute(): ?string
    {
        return $this->variantAttributeValue?->productAttributeValue?->value;
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

    public function getThumbnailUrlAttribute(): string
    {
        return $this->thumbnail
            ? asset('storage/' . $this->thumbnail->image)
            : asset('images/no-image.jpg');
    }

    public function getFormattedPriceAttribute(): string
    {
        return $this->price ? number_format($this->price) . ' تومان' : '---';
    }

    public function getFormattedSalePriceAttribute(): string
    {
        return $this->sale_price ? number_format($this->sale_price) . ' تومان' : '---';
    }

    public function getFinalPriceAttribute()
    {
        return $this->sale_price ?? $this->price;
    }

}
