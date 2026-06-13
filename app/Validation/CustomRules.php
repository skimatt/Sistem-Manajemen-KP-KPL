<?php

namespace App\Validation;

class CustomRules
{
    public function gpa_min(string $value, string $params): bool
    {
        return is_numeric($value) && (float) $value >= (float) $params;
    }

    public function sks_min(string $value, string $params): bool
    {
        return ctype_digit((string) $value) && (int) $value >= (int) $params;
    }

    public function alpha_space(string $value): bool
    {
        return (bool) preg_match('/^[\pL\s\.\,\-]+$/u', $value);
    }
}
