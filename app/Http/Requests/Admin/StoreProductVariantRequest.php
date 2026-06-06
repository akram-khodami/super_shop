<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductVariantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'sku' => ['required', 'string', 'max:255', 'unique:product_variants,sku'],
            'price' => ['required', 'numeric', 'min:0'],
            'sale_price' => ['nullable', 'numeric', 'min:0', 'lte:price'],
            'stock' => ['required', 'integer', 'min:0'],
            'is_active' => ['boolean'],
            'attribute_values' => ['required', 'array', 'min:1'],
            'attribute_values.*' => ['required', 'exists:product_attribute_values,id'],
            'image' => [
                'nullable',
                'image',
                'max:2048',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'price.required' => 'قیمت الزامی است',
            'stock.required' => 'موجودی الزامی است',
            'attribute_values.required' => 'حداقل یک ویژگی باید انتخاب شود',
            'attribute_values.*.exists' => 'مقدار ویژگی انتخاب شده معتبر نیست',
        ];
    }
}
