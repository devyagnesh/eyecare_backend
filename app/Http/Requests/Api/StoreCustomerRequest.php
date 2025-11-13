<?php

namespace App\Http\Requests\Api;

use App\Rules\PhoneNumber;
use App\Rules\ValidEmail;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCustomerRequest extends FormRequest
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
        $storeId = $this->getStoreId();
        
        $emailRules = ['nullable', 'string', 'max:255', new ValidEmail()];
        $phoneRules = ['required', 'string', 'max:20', new PhoneNumber()];
        
        if ($storeId) {
            $emailRules[] = Rule::unique('customers')->where(function ($query) use ($storeId) {
                return $query->where('store_id', $storeId);
            });
            $phoneRules[] = Rule::unique('customers')->where(function ($query) use ($storeId) {
                return $query->where('store_id', $storeId);
            });
        }
        
        return [
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'email' => $emailRules,
            'phone_number' => $phoneRules,
            'address' => [
                'nullable',
                'string',
            ],
        ];
    }

    /**
     * Get store ID from authenticated user.
     *
     * @return int|null
     */
    private function getStoreId(): ?int
    {
        if (!$this->user()) {
            return null;
        }
        
        $store = \App\Models\Store::where('user_id', $this->user()->id)->first();
        return $store ? $store->id : null;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Customer name is required.',
            'name.string' => 'Customer name must be a string.',
            'name.max' => 'Customer name must not exceed 255 characters.',
            'email' => 'Please provide a valid email address.',
            'email.max' => 'Email address must not exceed 255 characters.',
            'email.unique' => 'A customer with this email already exists in your store.',
            'phone_number.required' => 'Phone number is required.',
            'phone_number.string' => 'Phone number must be a string.',
            'phone_number.max' => 'Phone number must not exceed 20 characters.',
            'phone_number' => 'The phone number must be a valid phone number (7-15 digits, optionally starting with +).',
            'phone_number.unique' => 'A customer with this phone number already exists in your store.',
            'address.string' => 'Address must be a string.',
        ];
    }

}

