<?php

namespace App\Http\Requests\Api;

use App\Rules\PhoneNumber;
use App\Rules\ValidEmail;
use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
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
            'customer_id' => 'required|integer|exists:customers,id',
            'eye_examination_id' => 'nullable|integer|exists:eye_examinations,id',
            'frame_photo' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:5120',
            'glass_details' => 'nullable|string|max:5000',
            'total_price' => 'required|numeric|min:0|max:999999.99',
            'expected_completion_date' => 'required|date|after_or_equal:today',
            'status' => 'nullable|string|in:pending,processing,completed,cancelled',
            'notes' => 'nullable|string|max:2000',
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
            'customer_id.required' => 'Customer is required.',
            'customer_id.exists' => 'Selected customer does not exist.',
            'eye_examination_id.exists' => 'Selected eye examination does not exist.',
            'frame_photo.image' => 'Frame photo must be an image file.',
            'frame_photo.mimes' => 'Frame photo must be a JPEG, PNG, or WebP image.',
            'frame_photo.max' => 'Frame photo must not exceed 5MB.',
            'total_price.required' => 'Total price is required.',
            'total_price.numeric' => 'Total price must be a valid number.',
            'total_price.min' => 'Total price must be at least 0.',
            'expected_completion_date.required' => 'Expected completion date is required.',
            'expected_completion_date.date' => 'Expected completion date must be a valid date.',
            'expected_completion_date.after_or_equal' => 'Expected completion date must be today or later.',
            'status.in' => 'Status must be one of: pending, processing, completed, cancelled.',
        ];
    }
}
