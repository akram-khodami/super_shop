<?php

namespace App\Models;

class ProductAttributeValue extends BaseModel
{
    protected $fillable = [
        'product_attribute_id',
        'value',
    ];

    public function productAttribute()
    {
        return $this->belongsTo(ProductAttribute::class);
    }

    // ✅ هر ProductAttributeValue حداکثر به یه Variant متصله
    public function variantValue()
    {
        return $this->hasOne(VariantValue::class);
    }
}
