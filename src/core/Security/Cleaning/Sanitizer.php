<?php

namespace Nxp\Core\Security\Cleaning;

class Sanitizer
{
    // Sanitize a string
    public static function sanitizeString($input)
    {
        // Strip HTML tags and encode special characters
        return htmlspecialchars(strip_tags($input), ENT_QUOTES, 'UTF-8');
    }

    // Sanitize an email address
    public static function sanitizeEmail($input)
    {
        $sanitized = filter_var($input, FILTER_SANITIZE_EMAIL);
        return filter_var($sanitized, FILTER_VALIDATE_EMAIL) ? $sanitized : false;
    }

    // Sanitize a URL
    public static function sanitizeURL($input)
    {
        return filter_var($input, FILTER_SANITIZE_URL);
    }

    // Sanitize an integer
    public static function sanitizeInt($input)
    {
        return filter_var($input, FILTER_SANITIZE_NUMBER_INT);
    }

    // Sanitize a float
    public static function sanitizeFloat($input)
    {
        return filter_var($input, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    }

    // Sanitize SQL input to prevent SQL injection
    // Note: Parameterized queries should always be used for the best protection against SQL injection.
    public static function sanitizeSQL($input)
    {
        return addslashes($input);
    }

    // Sanitize a filename to remove any malicious characters
    public static function sanitizeFilename($input)
    {
        return preg_replace('/[^a-zA-Z0-9._\-]/', '', $input);
    }

    // Sanitize input for JavaScript (useful for data that will be embedded in a script)
    public static function sanitizeForJS($input)
    {
        return json_encode($input);
    }

    // Sanitize input that will be placed inside an HTML attribute
    public static function sanitizeForAttribute($input)
    {
        return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    }

    // Sanitize input that will be used as a CSS value
    public static function sanitizeForCSS($input)
    {
        return preg_replace('/[^a-zA-Z0-9.#\- ]/', '', $input);
    }
}
