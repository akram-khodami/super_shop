<?php

namespace App\Http\Requests\Admin;

use App\Rules\UniqueVariant;

class StoreProductVariantRequest extends BaseProductVariantRequest
{
    public function rules(): array
    {
        $product = $this->getProduct();

        $rules = array_merge($this->baseRules(), [
            'sku' => ['required', 'string', 'max:255', 'unique:variants,sku'],
            'attribute_value' => [
                'nullable',
                'exists:product_attribute_values,id',
            ],
        ]);

        // اضافه کردن اعتبارسنجی شرطی برای محصولاتی که ویژگی تنوع دارند
        if ($product) {
            $hasVariantAttribute = \App\Models\ProductAttribute::where('product_id', $product->id)
                ->where('is_variant', true)
                ->exists();

            if ($hasVariantAttribute) {
                // محصول متغیر: attribute_value الزامی است
                $rules['attribute_value'] = [
                    'required',
                    'exists:product_attribute_values,id',
                    new UniqueVariant($product),
                ];
            } else {
                // محصول ساده: چک کن که variant تکراری نباشد
                $rules['attribute_value'][] = new UniqueVariant($product);
            }
        }

        return $rules;
    }

    public function messages(): array
    {
        return array_merge(parent::messages(), [
            'attribute_value.required' => 'انتخاب مقدار ویژگی تنوع الزامی است',
        ]);
    }
}
