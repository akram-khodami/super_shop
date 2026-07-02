<?php

namespace App\ViewModels\Shop;

use App\Models\Product;
use App\Models\Variant;
use Illuminate\Support\Collection;

final class ProductViewModel
{
    public function __construct(
        private readonly Product $product
    ) {}

    public function specificationAttributes(): Collection
    {
        return $this->product->productAttributes
            ->where('is_variant', false)
            ->map(function ($attribute) {
                return [
                    'name' => $attribute->attribute->name,
                    'values' => $attribute->values->pluck('value'),
                ];
            });
    }

    public function variantAttributeData(): ?array
    {
        $attribute = $this->product
            ->productAttributes
            ->firstWhere('is_variant', true);

        if (! $attribute) {
            return null;
        }

        return [
            'name' => $attribute->attribute->name,
            'values' => $attribute->values->map(fn($value) => [
                'id' => $value->id,
                'value' => $value->value,
            ]),
        ];
    }

    public function selectedVariant(): ?Variant
    {
        return $this->product->selectedVariant();
    }

    public function variantsData(): Collection
    {
        return $this->product->variants
            ->map(fn(Variant $variant) => (new VariantViewModel($variant))->toArray());
    }

    public function variantOptions(): Collection
    {
        $variantAttribute = $this->variantAttributeData();

        if (! $variantAttribute) {
            return collect();
        }

        $variants = $this->product->variants
            ->keyBy(
                fn($variant) =>
                $variant->variantAttributeValue?->product_attribute_value_id
            );

        $selectedVariant = $this->selectedVariant();

        return collect($variantAttribute['values'])
            ->map(function ($value) use ($variants, $selectedVariant) {

                $variant = $variants->get($value['id']);

                return [
                    'id' => $value['id'],
                    'label' => $value['value'],
                    'variant_id' => $variant?->id,
                    'selected' =>
                    $selectedVariant?->variantAttributeValue?->product_attribute_value_id === $value['id'],
                    'disabled' => ! $variant || ! $variant->isAvailable(),
                ];
            });
    }


    public function gallery(): Collection
    {
        return $this->product->images
            ->take(5)
            ->map(function ($image) {
                return [
                    'id' => $image->id,
                    'thumbnail_url' => asset('storage/' . $image->image),
                    'is_primary' => $image->is_primary,
                ];
            });
    }
}
