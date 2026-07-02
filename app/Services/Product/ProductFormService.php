<?php

namespace App\Services\Product;

use App\Models\Attribute;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Collection;

class ProductFormService
{
    public function forCreate(): array
    {
        return [
            'categories' => Category::orderBy('name')
                ->get(['id', 'name']),

            'brands' => Brand::orderBy('name')
                ->get(['id', 'name']),
        ];
    }

    public function forEdit(Product $product): array
    {
        //ToDO:stock count

        $this->loadRelations($product);

        $productAttributes = $this->mapProductAttributes($product);

        return [
            ...$this->forCreate(),
            'product' => $product,
            'productAttributes' => $productAttributes,
            'availableAttributes' => $this->availableAttributes($productAttributes),
        ];
    }

    private function loadRelations(Product $product): void
    {
        $product->loadMissing([
            'images',
            'variants',
            'productAttributes.attribute',
            'productAttributes.values',
        ]);
    }

    private function mapProductAttributes(Product $product): Collection
    {
        // Registered attributes for this product (grouped)
        $productAttributes = $product->productAttributes
            ->map(function ($productAttribute) {
                return [
                    'id' => $productAttribute->id,
                    'attribute_id' => $productAttribute->attribute_id,
                    'name' => $productAttribute->attribute->name,
                    'is_variant' => $productAttribute->is_variant,
                    'values' => $productAttribute->values->map(function ($value) {
                        return [
                            'id' => $value->id,
                            'value' => $value->value,
                        ];
                    }),
                ];
            });

        return $productAttributes;
    }

    private function availableAttributes(Collection $productAttributes): Collection
    {
        // All system attributes
        $allAttributes = Attribute::query()
            ->orderBy('name')
            ->get(['id', 'name']);

        // Only non-variant attributes should be excluded.
        $nonVariantAttributeIds = $productAttributes
            ->where('is_variant', false)
            ->pluck('attribute_id');

        return $allAttributes->whereNotIn('id', $nonVariantAttributeIds);
    }
}
