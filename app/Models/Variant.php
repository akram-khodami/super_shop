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

    protected $appends = [
        'thumbnail_url',
        'formatted_price',
        'formatted_sale_price',
        'final_price',
        'in_stock_title',
    ];

    // ... relationships ...

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    /**
     * Get stock status text (without hardcoded language)
     * Use translation in view instead
     */
    public function getInStockTitleAttribute(): string
    {
        return $this->stock > 0 ? '✓' . __('messages.in_stock') : '✗' . __('messages.out_of_stock');
    }

    /**
     * Get stock status with icon (without hardcoded language)
     */
    public function getStockStatusAttribute(): array
    {
        return [
            'status' => $this->stock > 0 ? 'in_stock' : 'out_of_stock',
            'in_stock' => $this->stock > 0,
            'stock' => $this->stock,
        ];
    }

    public function variantAttributeValue(): HasOne
    {
        return $this->hasOne(VariantAttributeValue::class);
    }

    public function getVariantValueAttribute(): ?string
    {
        return $this->variantAttributeValue ?->productAttributeValue ?->value;
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

    /**
     * Format price without hardcoded currency
     * Currency should be added in view
     */
    public function getFormattedPriceAttribute(): string
    {
        return $this->price ? number_format($this->price) : '---';
    }

    public function getFormattedSalePriceAttribute(): string
    {
        return $this->sale_price ? number_format($this->sale_price) : '---';
    }

    public function getFinalPriceAttribute()
    {
        return $this->sale_price ?? $this->price;
    }

    /**
     * Get price data for JavaScript
     */
    public function getPriceDataAttribute(): array
    {
        return [
            'price' => $this->price,
            'sale_price' => $this->sale_price,
            'formatted_price' => $this->formatted_price,
            'formatted_sale_price' => $this->formatted_sale_price,
            'final_price' => $this->final_price,
            'has_discount' => $this->sale_price && $this->sale_price < $this->price,
            'discount_percent' => $this->sale_price && $this->price > 0
                ? round((($this->price - $this->sale_price) / $this->price) * 100)
                : 0,
        ];
    }

    /**
     * Check if variant is available
     */
    public function isAvailable(): bool
    {
        return $this->stock > 0 && $this->is_active;
    }

    /**
     * Check if variant has discount
     */
    public function hasDiscount(): bool
    {
        return $this->sale_price && $this->sale_price < $this->price;
    }

    /**
     * Get discount percentage
     */
    public function getDiscountPercentAttribute(): int
    {
        if (!$this->hasDiscount() || $this->price <= 0) {
            return 0;
        }

        return round((($this->price - $this->sale_price) / $this->price) * 100);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'variant_id');
    }
}
