<?php

namespace Nxp\Core\Utils\Form\Validation;

class Validator
{
    public static function required($value)
    {
        return !empty(trim($value));
    }

    public static function minLength($value, $length)
    {
        return strlen(trim($value)) >= $length;
    }

    public static function maxLength($value, $length)
    {
        return strlen(trim($value)) <= $length;
    }

    public static function email($value)
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }

    // Add more validation rules as needed...
}