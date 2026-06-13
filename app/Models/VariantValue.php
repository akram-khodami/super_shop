<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VariantValue extends Model
{
    protected $fillable = ['variant_id', 'product_attribute_value_id'];

    public function variant()
    {
        return $this->belongsTo(Variant::class);
    }

    public function productAttributeValue()
    {
        return $this->belongsTo(ProductAttributeValue::class);
    }

}
