<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductVariantImage;
use App\Models\Variant;
use App\Models\VariantAttributeValue;
use Illuminate\Support\Facades\DB;

class ProductVariantService
{
    public function create(Product $product, array $data): Variant
    {

        return DB::transaction(function () use ($product, $data) {

            $productAttributeValueId = $data['attribute_value'] ?? null;

            $images = $data['images'] ?? [];

            unset($data['images']);

            $variant = $product->variants()->create([
                'sku' => $data['sku'] ?? null,
                'price' => $data['price'],
                'sale_price' => $data['sale_price'] ?? null,
                'stock' => 0,
                'is_active' => $data['is_active'] ?? true,
            ]);

            if (!empty($productAttributeValueId)) {

                VariantAttributeValue::firstOrCreate([
                    'variant_id' => $variant->id,
                    'product_attribute_value_id' => $productAttributeValueId,
                ]);
            }

            foreach ($images as $index => $image) {

                $path = $image->store(
                    'products/variants',
                    'public'
                );

                ProductVariantImage::create([
                    'variant_id' => $variant->id,
                    'image' => $path,
                    'sort_order' => $index,
                ]);
            }


            return $variant;
        });
    }

    public function update(Variant $variant, array $data): Variant
    {
        $images = $data['images'] ?? [];

        unset($data['images']);

        return DB::transaction(function () use ($variant, $data, $images) {
            $variant->update([
                'sku' => $data['sku'] ?? null,
                'price' => $data['price'],
                'sale_price' => $data['sale_price'] ?? null,
                'stock' => 0,
                'is_active' => $data['is_active'] ?? $variant->is_active,
            ]);

            $productAttributeValueId = $data['attribute_value'] ?? null;

            if (!empty($productAttributeValueId)) {
                VariantAttributeValue::firstOrCreate([
                    'variant_id' => $variant->id,
                    'product_attribute_value_id' => $productAttributeValueId,
                ]);
            }

            if (!empty($images)) {

                foreach ($images as $index => $image) {

                    $path = $image->store(
                        'products/variants',
                        'public'
                    );

                    $variant->images()->create([
                        'image' => $path,
                        'sort_order' => $index,
                    ]);
                }
            }

            return $variant;
        });
    }
}
