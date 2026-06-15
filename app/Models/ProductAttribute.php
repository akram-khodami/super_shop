<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductAttribute extends Model
{
    protected $fillable = [
        'attribute_id',
        'product_id',
        'is_variant',
    ];

    //===All values of an attribute
    public function values()
    {
        return $this->hasMany(ProductAttributeValue::class);
    }

    //Todo:ببین اسم خوبه؟
    //===Only values of an attribute which use for variant(is_variant is true)
    public function variantValues()
    {
        return $this->hasMany(ProductAttributeValue::class)
            ->whereHas('productAttribute', function ($q) {
                $q->where('is_variant', true);
            });
    }

    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
