<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductVariantImage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductVariantService
{
    public function create(Product $product, array $data): ProductVariant
    {

        if (
        $this->variantExists(
            $product,
            $data['attribute_values']
        )
        ) {
            throw ValidationException::withMessages([
                'attribute_values' => 'این تنوع قبلاً ثبت شده است.'
            ]);
        }

        return DB::transaction(function () use ($product, $data) {

            $images = $data['images'] ?? [];

            unset($data['images']);

            $variant = $product->variants()->create([
                'sku' => $data['sku'] ?? null,
                'price' => $data['price'],
                'sale_price' => $data['sale_price'] ?? null,
                'stock' => $data['stock'],
                'is_active' => $data['is_active'] ?? true,
            ]);

            if (!empty($data['attribute_values'])) {
                $variant->attributeValues()->sync($data['attribute_values']);
            }

            foreach ($images as $index => $image) {

                $path = $image->store(
                    'products/variants',
                    'public'
                );

                ProductVariantImage::create([
                    'product_variant_id' => $variant->id,
                    'image' => $path,
                    'sort_order' => $index,
                ]);
            }


            return $variant;
        });
    }

    public function update(ProductVariant $variant, array $data): ProductVariant
    {
        $images = $data['images'] ?? [];

        unset($data['images']);

        return DB::transaction(function () use ($variant, $data, $images) {
            $variant->update([
                'sku' => $data['sku'] ?? null,
                'price' => $data['price'],
                'sale_price' => $data['sale_price'] ?? null,
                'stock' => $data['stock'],
                'is_active' => $data['is_active'] ?? $variant->is_active,
            ]);

            if (!empty($data['attribute_values'])) {
                $variant->attributeValues()->sync($data['attribute_values']);
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

    private function variantExists(
        Product $product,
        array $attributeValueIds,
        ?int $ignoreVariantId = null
    ): bool
    {

        sort($attributeValueIds);

        return $product->variants()
            ->with('attributeValues:id')
            ->get()
            ->filter(function ($variant) use (
                $attributeValueIds,
                $ignoreVariantId
            ) {

                if (
                    $ignoreVariantId &&
                    $variant->id === $ignoreVariantId
                ) {
                    return false;
                }

                $ids = $variant
                    ->attributeValues
                    ->pluck('id')
                    ->sort()
                    ->values()
                    ->toArray();

                return $ids === $attributeValueIds;

            })
            ->isNotEmpty();
    }
}
