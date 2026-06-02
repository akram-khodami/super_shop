<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;

class ShopSeeder extends Seeder
{
    public function run(): void
    {
        Category::factory(10)->create();

        Brand::factory(15)->create();

        Product::factory(100)
            ->create()
            ->each(function ($product) {

                ProductImage::factory(
                    fake()->numberBetween(1, 5)
                )->create([
                    'product_id' => $product->id,
                ]);

            });
    }
}
