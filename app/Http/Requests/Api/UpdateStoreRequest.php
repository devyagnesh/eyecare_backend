<?php

namespace App\Http\Requests\Api;

use App\Rules\PhoneNumber;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStoreRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $storeId = $this->user()->store->id ?? null;
        
        return [
            'name' => [
                'sometimes',
                'required',
                'string',
                'max:255',
            ],
            'logo' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,gif,svg',
                'max:2048',
            ],
            'email' => [
                'sometimes',
                'required',
                'email',
                'max:255',
                $storeId ? Rule::unique('stores', 'email')->ignore($storeId) : 'unique:stores,email',
            ],
            'phone_number' => [
                'sometimes',
                'required',
                'string',
                'max:20',
                new PhoneNumber(),
                $storeId ? Rule::unique('stores', 'phone_number')->ignore($storeId) : 'unique:stores,phone_number',
            ],
            'address' => [
                'sometimes',
                'required',
                'string',
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Store name is required.',
            'name.string' => 'Store name must be a string.',
            'name.max' => 'Store name must not exceed 255 characters.',
            'logo.image' => 'Logo must be an image file.',
            'logo.mimes' => 'Logo must be a file of type: jpeg, png, jpg, gif, svg.',
            'logo.max' => 'Logo must not exceed 2048 kilobytes.',
            'email.required' => 'Email address is required.',
            'email.email' => 'Please provide a valid email address.',
            'email.max' => 'Email address must not exceed 255 characters.',
            'email.unique' => 'A store with this email already exists.',
            'phone_number.required' => 'Phone number is required.',
            'phone_number.string' => 'Phone number must be a string.',
            'phone_number.max' => 'Phone number must not exceed 20 characters.',
            'phone_number' => 'The phone number must be a valid phone number (7-15 digits, optionally starting with +).',
            'phone_number.unique' => 'A store with this phone number already exists.',
            'address.required' => 'Address is required.',
            'address.string' => 'Address must be a string.',
        ];
    }
}

