<?php

namespace App\ViewModels\Shop;

use App\Models\Variant;
use Illuminate\Support\Collection;

class VariantViewModel
{

    public function __construct(
        private readonly Variant $variant
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->variant->id,
            'value_id' => $this->variant->variantAttributeValue?->product_attribute_value_id,
            'price' => $this->variant->price,
            'sale_price' => $this->variant->sale_price,
            'stock' => $this->variant->stock,
            'image' => $this->variant->thumbnail_url,
            'in_stock' => $this->variant->isAvailable(),
            'status' => $this->stockTitle(),
            'formatted_price' => $this->formattedPrice(),
            'formatted_sale_price' => $this->formattedSalePrice(),
        ];
    }

    private function formattedPrice(): string
    {
        return $this->variant->price
            ? number_format($this->variant->price)
            : '---';
    }

    private function formattedSalePrice(): string
    {
        return $this->variant->sale_price
            ? number_format($this->variant->sale_price)
            : '---';
    }

    public function stockTitle(): string
    {
        return $this->variant->stock > 0 ? '✓' . __('messages.in_stock') : '✗' . __('messages.out_of_stock');
    }

    public function stockStatus(): array
    {
        return [
            'status' => $this->variant->stock > 0 ? 'in_stock' : 'out_of_stock',
            'in_stock' => $this->variant->stock > 0,
            'stock' => $this->variant->stock,
        ];
    }
}
