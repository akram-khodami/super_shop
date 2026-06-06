<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */

    public function rules(): array
    {
        return [

            'category_id' => ['required', 'exists:categories,id'],

            'brand_id' => ['nullable', 'exists:brands,id'],

            'name' => ['required', 'string', 'max:255'],

            'description' => ['nullable'],

            'sku' => [
                'required',
                Rule::unique('products')
                    ->ignore($this->product)
            ],

            'price' => ['required', 'numeric'],

            'sale_price' => ['nullable', 'numeric'],

            'stock' => ['required', 'integer'],

            'featured' => ['nullable', 'boolean'],

            'is_active' => ['nullable', 'boolean'],

            'attributes' => 'sometimes|array',
            'attributes.*' => 'integer|exists:product_attributes,id',
        ];
    }
}
