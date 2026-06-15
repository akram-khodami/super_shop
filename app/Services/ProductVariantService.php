<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductVariantImage;
use App\Models\Variant;
use App\Models\VariantAttributeValue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductVariantService
{
    public function create(Product $product, array $data): Variant
    {

        if (!empty($data['attribute_value'])) {

            if (
            $this->variantExists(
                $product,
                $data['attribute_value']
            )
            ) {
                throw ValidationException::withMessages([
                    'attribute_value' => 'این تنوع قبلاً ثبت شده است.'
                ]);
            }

        } else {

            //ToDO:قبلا رکورد تنوعی درج نشده باشد
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

            if (!empty($data['attribute_value'])) {

                VariantAttributeValue::firstOrCreate([
                    'variant_id' => $variant->id,
                    'product_attribute_value_id' => $data['attribute_value'],
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
                'stock' => $data['stock'],
                'is_active' => $data['is_active'] ?? $variant->is_active,
            ]);

            if (!empty($data['attribute_value'])) {
                VariantAttributeValue::firstOrCreate([
                    'variant_id' => $variant->id,
                    'product_attribute_value_id' => $data['attribute_value'],
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

    private function variantExists(
        Product $product,
        int $attributeValueId,
        ?int $ignoreVariantId = null
    ): bool
    {

        return $product->variants()
            ->with('attributeValue:id')
            ->get()
            ->filter(function ($variant) use (
                $attributeValueId,
                $ignoreVariantId
            ) {

                if (
                    $ignoreVariantId &&
                    $variant->id === $ignoreVariantId
                ) {
                    return false;
                }

            })
            ->isNotEmpty();
    }
}
