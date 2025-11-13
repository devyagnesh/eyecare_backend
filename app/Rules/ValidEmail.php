<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidEmail implements ValidationRule
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
        // Allow null/empty values (for nullable fields)
        if (empty($value)) {
            return;
        }

        if (!is_string($value)) {
            $fail('The :attribute must be a valid email address.');
            return;
        }

        // Trim whitespace
        $value = trim($value);

        // Basic format validation using filter_var
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $fail('The :attribute must be a valid email address.');
            return;
        }

        // Additional RFC 5322 compliant validation
        // Check for valid email format: local@domain
        if (!preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $value)) {
            $fail('The :attribute must be a valid email address.');
            return;
        }

        // Check length (max 255 characters as per database)
        if (strlen($value) > 255) {
            $fail('The :attribute must not exceed 255 characters.');
            return;
        }

        // Check for consecutive dots
        if (strpos($value, '..') !== false) {
            $fail('The :attribute must be a valid email address.');
            return;
        }

        // Check that domain part has at least one dot
        $parts = explode('@', $value);
        if (count($parts) !== 2 || !strpos($parts[1], '.')) {
            $fail('The :attribute must be a valid email address.');
            return;
        }
    }
}

