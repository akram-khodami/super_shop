<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attribute extends BaseModel
{
    /** @use HasFactory<\Database\Factories\AttributeFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'type',
    ];

    //checked
    public function products()
    {
        return $this->belongsToMany(
            Product::class,
            'product_attributes',
            'attribute_id',  // foreign key related to this Model
            'product_id'              // foreign key related to Product Model
        )->withPivot('is_variant');
    }

    //لازمه حذف بشه؟کاربردی نیست.
    public function productAttributes()
    {
        return $this->hasMany(ProductAttribute::class);
    }
}
