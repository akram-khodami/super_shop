<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;

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
}
