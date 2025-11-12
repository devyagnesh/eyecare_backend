<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Store Setting API Request
 * 
 * Validates API setting creation requests.
 * 
 * @package App\Http\Requests\Api
 */
class StoreSettingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null; // Must be authenticated
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'key' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-z0-9_]+$/i',
                'unique:settings,key',
            ],
            'value' => [
                'nullable',
                'string',
            ],
            'type' => [
                'required',
                'string',
                Rule::in(['string', 'integer', 'boolean', 'json', 'text', 'float']),
            ],
            'group' => [
                'required',
                'string',
                'max:100',
            ],
            'description' => [
                'nullable',
                'string',
                'max:500',
            ],
            'is_public' => [
                'nullable',
                'boolean',
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
            'key.required' => 'Setting key is required.',
            'key.unique' => 'A setting with this key already exists.',
            'key.regex' => 'Setting key can only contain letters, numbers, and underscores.',
            'type.required' => 'Setting type is required.',
            'type.in' => 'Invalid setting type.',
            'group.required' => 'Setting group is required.',
        ];
    }
}

