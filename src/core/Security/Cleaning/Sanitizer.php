<?php

namespace Nxp\Core\Security\Cleaning;

use PDO;

class Sanitizer
{
    /**
     * Sanitize input data by removing HTML tags and special characters.
     *
     * @param mixed $data The input data to sanitize.
     * @param bool $allowHtmlTags Flag to allow certain HTML tags (optional).
     * @return mixed The sanitized data.
     */
    public static function sanitizeInput($data, $allowHtmlTags = false)
    {
        if (is_array($data)) {
            return array_map([__CLASS__, 'sanitizeInput'], $data, array_fill(0, count($data), $allowHtmlTags));
        } else {
            // Use PHP's built-in functions to sanitize data
            $data = trim($data);
            $data = stripslashes($data);
            if (!$allowHtmlTags) {
                $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
            }
            return $data;
        }
    }

    /**
     * Sanitize input data for safe use in SQL queries.
     *
     * @param mixed $data The input data to sanitize.
     * @param PDO $pdo The database connection object (optional).
     * @return mixed The sanitized data.
     */
    public static function sanitizeSQL($data, $pdo = null)
    {
        if (is_array($data)) {
            return array_map([__CLASS__, 'sanitizeSQL'], $data, array_fill(0, count($data), $pdo));
        } else {
            // If you are using PDO, it's recommended to use prepared statements instead of this method.
            // However, this method can provide an additional layer of security.
            if ($pdo instanceof PDO) {
                $data = $pdo->quote($data);
            } else {
                // Use PHP's built-in functions to sanitize data for SQL queries
                $data = addslashes($data);
            }
            return $data;
        }
    }

    /**
     * Sanitize input data by removing all HTML tags.
     *
     * @param mixed $data The input data to strip HTML tags.
     * @return mixed The data with HTML tags removed.
     */
    public static function stripHTMLTags($data)
    {
        if (is_array($data)) {
            return array_map([__CLASS__, 'stripHTMLTags'], $data);
        } else {
            // Use PHP's built-in function to remove HTML tags
            return strip_tags($data);
        }
    }

    /**
     * Sanitize input data by converting it to a safe filename.
     *
     * @param string $filename The input filename to sanitize.
     * @return string The sanitized filename.
     */
    public static function sanitizeFilename($filename)
    {
        // Replace all potentially dangerous characters in the filename
        $filename = preg_replace('/[^a-zA-Z0-9\.\-_]/', '_', $filename);

        // Remove any leading/trailing dots or dashes
        $filename = trim($filename, '.-');

        // Limit the filename length to avoid excessively long filenames
        $maxFilenameLength = 255;
        $filename = substr($filename, 0, $maxFilenameLength);

        return $filename;
    }

    /**
     * Validate an email address.
     *
     * @param string $email The email address to validate.
     * @return bool True if the email is valid, false otherwise.
     */
    public static function isValidEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Validate a URL.
     *
     * @param string $url The URL to validate.
     * @return bool True if the URL is valid, false otherwise.
     */
    public static function isValidURL($url)
    {
        return filter_var($url, FILTER_VALIDATE_URL) !== false;
    }
}
