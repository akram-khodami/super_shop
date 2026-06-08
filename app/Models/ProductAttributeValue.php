<?php

namespace App\Models;

class ProductAttributeValue extends BaseModel
{
    protected $fillable = [
        'product_attribute_id',
        'value',
    ];

    public function attribute()
    {
        return $this->belongsTo(
            ProductAttribute::class
        );
    }

    public function variants()
    {
        return $this->belongsToMany(
            ProductVariant::class,
            'product_variant_attribute_values'
        );
    }

}
