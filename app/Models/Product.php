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

//    protected $guarded = [];

    protected $fillable = [
        'id',
        'category_id',
        'brand_id',

        'name',
        'slug',
        'description',

        'sku',

        'price',
        'sale_price',

        'stock',

        'featured',
        'is_active',

        'created_at',
        'updated_at',
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
        return number_format($this->price) . ' تومان';
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
            ->when(
                request('stock') === 'out',
                fn ($q) => $q->where(
                    'stock',
                    0
                )
            )
            ->when(
                request('stock') === 'in',
                fn ($q) => $q->where(
                    'stock',
                    '>',
                    0
                )
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

                    'price_asc'
                => $q->orderBy('price'),

            'price_desc'
                => $q->orderByDesc('price'),

            'name'
                => $q->orderBy('name'),

            default
                => $q->latest(),
        };
    }
            );
    }
}
