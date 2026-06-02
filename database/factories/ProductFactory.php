<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->words(3, true);

        $price = fake()->numberBetween(50, 5000);

        return [

            'category_id' => Category::inRandomOrder()->first()?->id,
        'brand_id' => Brand::inRandomOrder()->first()?->id,

        'name' => ucfirst($name),

        'slug' => Str::slug($name.'-'.fake()->unique()->numberBetween(1,99999)),

        'description' => fake()->paragraphs(4, true),

        'sku' => strtoupper(fake()->unique()->bothify('SKU-#####')),

        'price' => $price,

        'sale_price' => fake()->boolean(30)
        ? $price - fake()->numberBetween(10, 100)
        : null,

        'stock' => fake()->numberBetween(0, 100),

        'featured' => fake()->boolean(20),

        'is_active' => true,
    ];
}
}
