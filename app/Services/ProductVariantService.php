<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductVariant;
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

        $imagePath = null;

        if (request()->hasFile('image')) {

            $imagePath = request()
                ->file('image')
                ->store(
                    'variants',
                    'public'
                );
        }

        return DB::transaction(function () use ($product, $data, $imagePath) {
            $variant = $product->variants()->create([
                'sku' => $data['sku'] ?? null,
                'price' => $data['price'],
                'sale_price' => $data['sale_price'] ?? null,
                'stock' => $data['stock'],
                'is_active' => $data['is_active'] ?? true,
                'image' => $imagePath,
            ]);

            if (!empty($data['attribute_values'])) {
                $variant->attributeValues()->sync($data['attribute_values']);
            }

            return $variant;
        });
    }

    public function update(ProductVariant $variant, array $data): ProductVariant
    {
        $imagePath = $variant->image;

        if (
        request()->hasFile('image')
        ) {

            if (
                $variant->image &&
                Storage::disk('public')
                    ->exists($variant->image)
            ) {

                Storage::disk('public')
                    ->delete($variant->image);
            }

            $imagePath = request()
                ->file('image')
                ->store(
                    'variants',
                    'public'
                );
        }

        return DB::transaction(function () use ($variant, $data, $imagePath) {
            $variant->update([
                'sku' => $data['sku'] ?? null,
                'price' => $data['price'],
                'sale_price' => $data['sale_price'] ?? null,
                'stock' => $data['stock'],
                'is_active' => $data['is_active'] ?? $variant->is_active,
                'image' => $imagePath,
            ]);

            if (!empty($data['attribute_values'])) {
                $variant->attributeValues()->sync($data['attribute_values']);
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
