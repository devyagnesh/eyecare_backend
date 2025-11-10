<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class PhoneNumber implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        if (empty($value)) {
            return false;
        }

        // Remove all non-digit characters for validation
        $cleaned = preg_replace('/[^0-9+]/', '', $value);
        
        // Phone number should be between 7 and 15 digits (international standard)
        // Allow + prefix for international numbers
        $pattern = '/^(\+?[0-9]{7,15})$/';
        
        return preg_match($pattern, $cleaned) === 1;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'The :attribute must be a valid phone number (7-15 digits, optionally starting with +).';
    }
}

