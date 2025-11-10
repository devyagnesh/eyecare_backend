<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PhoneNumber implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string, ?string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (empty($value) || !is_string($value)) {
            $fail('The :attribute must be a valid phone number (7-15 digits, optionally starting with +).');
            return;
        }

        // Remove all non-digit characters except + for validation
        $cleaned = preg_replace('/[^0-9+]/', '', $value);
        
        // Phone number should be between 7 and 15 digits (international standard)
        // Allow + prefix for international numbers
        // Pattern: optional + followed by 7-15 digits
        $pattern = '/^\+?[0-9]{7,15}$/';
        
        if (preg_match($pattern, $cleaned) !== 1) {
            $fail('The :attribute must be a valid phone number (7-15 digits, optionally starting with +).');
        }
    }
}

