<?php

namespace App\Http\Requests\Admin;

use App\Models\ProductAttribute;
use App\Rules\UniqueVariant;
use Illuminate\Validation\Rule;

class UpdateProductVariantRequest extends BaseProductVariantRequest
{
    public function rules(): array
    {
        $product = $this->getProduct();
        $variantId = $this->route('variant')?->id;

        $rules = array_merge($this->baseRules(), [
            'sku' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('variants', 'sku')->ignore($variantId),
            ],
            'attribute_value' => [
                'nullable',
                'exists:product_attribute_values,id',
            ],
        ]);

        // اضافه کردن اعتبارسنجی شرطی برای محصولاتی که ویژگی تنوع دارند
        if ($product) {
            $hasVariantAttribute = ProductAttribute::where('product_id', $product->id)
                ->where('is_variant', true)
                ->exists();

            if ($hasVariantAttribute) {
                // محصول متغیر: attribute_value الزامی است
                $rules['attribute_value'] = [
                    'required',
                    'exists:product_attribute_values,id',
                    new UniqueVariant($product, $variantId),
                ];
            } else {
                // محصول ساده: چک کن که variant تکراری نباشد
                $rules['attribute_value'][] = new UniqueVariant($product, $variantId);
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
