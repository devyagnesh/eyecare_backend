<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreNotificationRequest extends FormRequest
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
        return [
            'title' => 'required|string|max:255',
            'body' => 'required|string|max:1000',
            'type' => 'required|in:all,user,store',
            'user_id' => 'required_if:type,user|exists:users,id',
            'store_id' => 'required_if:type,store|exists:stores,id',
            'data' => 'nullable|array',
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
            'title.required' => 'The title field is required.',
            'title.max' => 'The title may not be greater than 255 characters.',
            'body.required' => 'The body field is required.',
            'body.max' => 'The body may not be greater than 1000 characters.',
            'type.required' => 'Please select a notification type.',
            'type.in' => 'The selected notification type is invalid.',
            'user_id.required_if' => 'Please select a user.',
            'user_id.exists' => 'The selected user is invalid.',
            'store_id.required_if' => 'Please select a store.',
            'store_id.exists' => 'The selected store is invalid.',
        ];
    }
}
