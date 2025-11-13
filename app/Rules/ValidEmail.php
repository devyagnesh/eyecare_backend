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

        // Check that domain part has at least one dot and is not empty
        $parts = explode('@', $value);
        if (count($parts) !== 2) {
            $fail('The :attribute must be a valid email address.');
            return;
        }
        
        $domain = $parts[1];
        
        // Domain must not be empty
        if (empty($domain)) {
            $fail('The :attribute must be a valid email address.');
            return;
        }
        
        // Domain must contain at least one dot
        if (strpos($domain, '.') === false) {
            $fail('The :attribute must be a valid email address.');
            return;
        }
        
        // Domain must have a valid TLD (at least 2 characters after the last dot)
        $domainParts = explode('.', $domain);
        $tld = end($domainParts);
        if (strlen($tld) < 2 || !ctype_alpha($tld)) {
            $fail('The :attribute must be a valid email address.');
            return;
        }
        
        // Domain cannot start or end with a dot or hyphen
        if (str_starts_with($domain, '.') || str_starts_with($domain, '-') || 
            str_ends_with($domain, '.') || str_ends_with($domain, '-')) {
            $fail('The :attribute must be a valid email address.');
            return;
        }
    }
}

