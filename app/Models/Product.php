<?php

namespace App\Models;

use App\Policies\ProductPolicy;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;


#[UsePolicy(ProductPolicy::class)]
class Product extends BaseModel
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

    protected $appends = [
        'thumbnail_url',
        'in_stock',
    ];

    // ... relationships ...

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(Variant::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    public function productAttributes(): HasMany
    {
        return $this->hasMany(ProductAttribute::class);
    }

    public function thumbnail()
    {
        return $this->hasOne(ProductImage::class)
            ->orderByDesc('is_primary')
            ->orderBy('sort_order');
    }

    public function attributes()
    {
        return $this->belongsToMany(
            Attribute::class,
            'product_attributes',
            'product_id',
            'attribute_id'
        )->withPivot('is_variant');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // ... scope ...

    public function scopeActive(Builder $query): Builder
    {
        return $query->whereIsActive(true);
    }

    public function scopeLowStock(Builder $query, int $threshold = 5): Builder
    {
        return $query->withSum('variants', 'stock')
            ->having('variants_sum_stock', '<=', $threshold)
            ->having('variants_sum_stock', '>', 0);
    }

    public function scopeOutOfStock(Builder $query): Builder
    {
        return $query->whereDoesntHave('variants', function ($q) {
            $q->where('stock', '>', 0);
        });
    }

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        $search = $filters['search'] ?? null;
        $categoryId = $filters['category_id'] ?? null;
        $brandId = $filters['brand_id'] ?? null;
        $status = $filters['status'] ?? null;
        $stock = $filters['stock'] ?? null;
        $trash = $filters['trash'] ?? null;
        $sort = $filters['sort'] ?? null;

        return $query
            ->when(
                $search,
                fn($q, $search) => $q->where('name', 'like', "%{$search}%")
            )
            ->when(
                $categoryId,
                fn($q, $categoryId) => $q->where('category_id', $categoryId)
            )
            ->when(
                $brandId,
                fn($q, $brandId) => $q->where('brand_id', $brandId)
            )
            ->when(
                array_key_exists('status', $filters),
                fn($q) => $q->whereIsActive($status)
            )
            ->when(
                $stock === 'out',
                fn($q) => $q->whereDoesntHave(
                    'variants',
                    fn($q2) => $q2->where('stock', '>', 0)
                )
            )
            ->when(
                $stock === 'in',
                fn($q) => $q->whereHas(
                    'variants',
                    fn($q2) => $q2->where('stock', '>', 0)
                )
            )
            ->when(
                $trash,
                function ($q, $trash) {
                    match ($trash) {
                        'only' => $q->onlyTrashed(),
                        'with' => $q->withTrashed(),
                        default => null,
                    };
                }
            )
            ->when(
                $sort,
                function ($q, $sort) {
                    match ($sort) {
                        'name' => $q->orderBy('name'),
                        default => $q->latest(),
                    };
                }
            );
    }

    // ... Attribute ...

    public function getInStockAttribute(): bool
    {
        return $this->relationLoaded('variants')
            ? $this->variants->sum('stock') > 0
            : $this->variants()->sum('stock') > 0;
    }

    public function getFirstAvailableVariantAttribute()
    {
        return $this->variants
            ->where('stock', '>', 0)
            ->first();
    }

    public function getThumbnailUrlAttribute(): string
    {
        return $this->thumbnail
            ? asset('storage/' . $this->thumbnail->image)
            : asset('images/no-image.jpg');
    }

    public function selectedVariant(): ?Variant
    {
        return $this->variants
            ->first(fn(Variant $variant) => $variant->isAvailable())
            ?? $this->variants->firstWhere('is_default', true)
            ?? $this->variants->first();
    }

    public function defaultVariant(): ?Variant
    {
        return $this->variants->firstWhere('is_default', true)
            ?? $this->variants->first();
    }
}
