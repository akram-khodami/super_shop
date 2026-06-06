<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductVariantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // می‌توانید منطق دسترسی را اضافه کنید
    }

    public function rules(): array
    {
        return [
            'sku' => ['nullable', 'string', 'max:255',
                Rule::unique('product_variants', 'sku')->ignore($this->route('variant'))
            ],
            'price' => ['required', 'numeric', 'min:0'],
            'sale_price' => ['nullable', 'numeric', 'min:0', 'lt:price'],
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
