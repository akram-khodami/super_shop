<?php

namespace App\Http\Requests\Admin;

use App\Models\ProductAttribute;
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
                Rule::unique('variants', 'sku')->ignore($this->route('variant'))
            ],
            'price' => ['required', 'numeric', 'min:0'],
            'sale_price' => ['nullable', 'numeric', 'min:0', 'lte:price'],
            'stock' => ['required', 'integer', 'min:0'],
            'is_active' => ['boolean'],
            'attribute_value' => [
                'nullable',
                'exists:product_attribute_values,id',
                function ($attribute, $value, $fail) {
                    $productId = $this->route('product') ?->id ?? $this->input('product_id');

                if (!$productId) return;

                $hasVariantAttribute = ProductAttribute::where('product_id', $productId)
                    ->where('is_variant', true)
                    ->exists();

                if ($hasVariantAttribute && empty($value)) {
                    $fail('انتخاب مقدار ویژگی تنوع الزامی است');
                }
            },
            ],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
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
