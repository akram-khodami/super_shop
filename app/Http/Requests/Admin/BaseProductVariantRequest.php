<?php

namespace App\Http\Requests\Admin;

use App\Models\Product;
use App\Models\ProductAttribute;
use App\Rules\UniqueVariant;
use Illuminate\Foundation\Http\FormRequest;

abstract class BaseProductVariantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function baseRules(): array
    {
        return [
            'price' => ['required', 'numeric', 'min:0'],
            'sale_price' => ['nullable', 'numeric', 'min:0', 'lte:price'],
            'is_active' => ['boolean'],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    protected function getProduct(): ?Product
    {
        if ($this->route('product')) {
            return $this->route('product');
        }

        $productId = $this->input('product_id');
        return $productId ? Product::find($productId) : null;
    }

    public function messages(): array
    {
        return [
            'price.required' => 'قیمت الزامی است',
            'attribute_value.required' => 'حداقل یک ویژگی باید انتخاب شود',
            'attribute_value.exists' => 'مقدار ویژگی انتخاب شده معتبر نیست',
        ];
    }
}
