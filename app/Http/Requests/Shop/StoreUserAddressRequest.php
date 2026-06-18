<?php

namespace App\Http\Requests\Shop;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserAddressRequest extends FormRequest
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
            'title' => ['required','string','max:255'],
            'recipient_name' => ['required','string','max:255'],
            'mobile' => ['required','string','max:14'],
            'province' => ['required','string','max:255'],
            'city' => ['required','string','max:255'],
            'address' => ['required','string'],
            'postal_code' => ['nullable','string','max:10'],
        ];
    }
}
