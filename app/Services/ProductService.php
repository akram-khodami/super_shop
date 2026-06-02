<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductService
{
    public function create(array $data): Product
    {
        return DB::transaction(function () use ($data) {

            $images = $data['images'] ?? [];

            unset($data['images']);

            $data['slug'] = str()->slug($data['name']);

            $product = Product::create($data);

            foreach ($images as $index => $image) {

                $path = $image->store(
                    'products',
                    'public'
                );

                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $path,
                    'sort_order' => $index,
                ]);
            }

            return $product;
        });
    }

    public function update(
        Product $product,
        array $data
    ): Product
    {

        $images = $data['images'] ?? [];

        unset($data['images']);

        $data['slug'] = str()->slug($data['name']);

        $product->update($data);

        foreach ($images as $index => $image) {

            $path = $image->store(
                'products',
                'public'
            );

            ProductImage::create([
                'product_id' => $product->id,
                'image' => $path,
                'sort_order' => $index,
            ]);
        }

        return $product;
    }
}
