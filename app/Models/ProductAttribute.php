<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductAttribute extends Model
{
    protected $fillable = [
        'name',
        'slug',
    ];

    public function values()
    {
        return $this->hasMany(
            ProductAttributeValue::class
        );
    }

    public function products()
    {
        return $this->belongsToMany(
            Product::class,
            'product_product_attribute',
            'product_attribute_id',  // foreign key مربوط به این مدل
            'product_id'              // foreign key مربوط به مدل Product
        );
    }
}
