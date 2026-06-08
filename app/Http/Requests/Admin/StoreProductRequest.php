<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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

            'name' => ['required', 'string', 'max:255', 'unique:products'],

            'description' => ['nullable'],

            'featured' => ['nullable', 'boolean'],

            'is_active' => ['nullable', 'boolean'],

            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }
}
