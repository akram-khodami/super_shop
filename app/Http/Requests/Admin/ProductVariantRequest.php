<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ProductVariantRequest extends FormRequest
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

            'name' => [
                'nullable',
                'string',
                'max:255'
            ],

            'sku' => [
                'required',
                'max:255'
            ],

            'price' => [
                'required',
                'numeric',
                'min:0'
            ],

            'sale_price' => [
                'nullable',
                'numeric',
                'min:0'
            ],

            'stock' => [
                'required',
                'integer',
                'min:0'
            ],

            'image' => [
                'nullable',
                'image'
            ],

            'is_active' => [
                'nullable',
                'boolean'
            ],
        ];
    }
}
