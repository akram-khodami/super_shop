<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'brand_id',
        'name',
        'slug',
        'description',
        'featured',
        'is_active',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    public function thumbnail()
    {
        return $this->hasOne(ProductImage::class)
            ->orderByDesc('is_primary')
            ->orderBy('sort_order');
    }

    public function getThumbnailUrlAttribute(): string
    {
        return $this->thumbnail
            ? asset('storage/' . $this->thumbnail->image)
            : asset('images/no-image.jpg');
    }

    public function variants()
    {
        return $this->hasMany(Variant::class);
    }

    public function attributes()
    {
        return $this->belongsToMany(
            Attribute::class,
            'product_attributes',  // اسم جدول pivot
            'product_id',                  // foreign key مربوط به این مدل
            'attribute_id'         // foreign key مربوط به مدل مقابل
        )->withPivot('is_variant');
    }

    public function productAttributes()
    {
        return $this->hasMany(ProductAttribute::class);
    }

    public function getInStockAttribute()
    {
        return $this->variants->sum('stock') > 0;
    }

    public function scopeOutOfStock(Builder $query): Builder
    {
        return $query->whereDoesntHave('variants', function ($q) {
            $q->where('stock', '>', 0);
        });
    }

    public function scopeLowStock(Builder $query, int $threshold = 5): Builder
    {
        return $query->withSum('variants', 'stock')
            ->having('variants_sum_stock', '<=', $threshold)
            ->having('variants_sum_stock', '>', 0);
    }

    public function getGalleryAttribute()
    {
        return $this->images->take(5);
    }

    public function getDefaultVariantAttribute()
    {
        return $this->variants->firstWhere('is_default', true)
            ?? $this->variants->first();
    }

    public function getFirstAvailableVariantAttribute()
    {
        return $this->variants
            ->where('stock', '>', 0)
            ->first();
    }

    public function getDisplayPriceAttribute()
    {
        return $this->first_available_variant ?->price;
    }

    public function getDisplaySalePriceAttribute()
    {
        return $this->first_available_variant ?->sale_price;
}

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query
            ->when(
                $filters['search'] ?? null,
                fn ($q, $search) => $q->where(
                    'name',
                    'like',
                    "%{$search}%"
                )
            )
            ->when(
                $filters['category_id'] ?? null,
                fn ($q, $categoryId) => $q->where(
                    'category_id',
                    $categoryId
                )
            )
            ->when(
                $filters['brand_id'] ?? null,
                fn ($q, $brandId) => $q->where(
                    'brand_id',
                    $brandId
                )
            )
            ->when(
                request('status') !== null,
                fn ($q) => $q->where(
                    'is_active',
                    request('status')
                )
            )
            ->when(request('stock') === 'out',
                fn ($q) => $q->whereDoesntHave('variants', fn ($q2) => $q2->where('stock', '>', 0))
            )
            ->when(request('stock') === 'in',
                fn ($q) => $q->whereHas('variants', fn ($q2) => $q2->where('stock', '>', 0))
            )
            ->when(
                $filters['trash'] ?? null,
                function ($q, $trash) {

                    if ($trash === 'only') {

                        $q->onlyTrashed();
                    }

                    if ($trash === 'with') {

                        $q->withTrashed();
                    }
                }
            )
            ->when(
                $filters['sort'] ?? null,
                function ($q, $sort) {

                    match($sort){


                    'name'
                => $q->orderBy('name'),

            default
                => $q->latest(),
        };
    }
            );
    }
}
