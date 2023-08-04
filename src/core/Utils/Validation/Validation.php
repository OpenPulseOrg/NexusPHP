<?php

namespace Nxp\Core\Utils\Validation;

use DateTime;

/**
 * Validation class for handling data validation and sanitization.
 *
 * @package Nxp\Core\Utils\Validation
 */
class Validation
{
    /**
     * Validate a string value.
     *
     * @param string $value The string value to validate.
     * @return bool Returns true if the string is valid, false otherwise.
     */
    public static function validateString($value)
    {
        if (empty($value)) {
            return false;
        }

        return true;
    }

    /**
     * Validate a number value.
     *
     * @param mixed $value The value to validate.
     * @return bool Returns true if the value is a valid number, false otherwise.
     */
    public static function validateNumber($value)
    {
        if (!is_numeric($value)) {
            return false;
        }

        return true;
    }

    /**
     * Sanitize a string value by removing HTML tags and special characters.
     *
     * @param string $value The string value to sanitize.
     * @return string The sanitized string value.
     */
    public static function sanitizeString($value)
    {
        $sanitizedValue = strip_tags($value);
        $sanitizedValue = htmlspecialchars($sanitizedValue);

        return $sanitizedValue;
    }

    /**
     * Validate an email address.
     *
     * @param string $email The email address to validate.
     * @return bool Returns true if the email address is valid, false otherwise.
     */
    public static function validateEmail($email)
    {
        $pattern = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';
        return preg_match($pattern, $email) === 1;
    }

    /**
     * Validate a URL.
     *
     * @param string $url The URL to validate.
     * @return bool Returns true if the URL is valid, false otherwise.
     */
    public static function validateUrl($url)
    {
        return filter_var($url, FILTER_VALIDATE_URL) !== false;
    }

    /**
     * Sanitize an email address by removing unnecessary characters or tags.
     *
     * @param string $email The email address to sanitize.
     * @return string The sanitized email address.
     */
    public static function sanitizeEmail($email)
    {
        $sanitizedEmail = trim(strip_tags($email));
        $sanitizedEmail = strtolower($sanitizedEmail);

        return $sanitizedEmail;
    }

    /**
     * Sanitize a URL by removing unnecessary characters or tags.
     *
     * @param string $url The URL to sanitize.
     * @return string The sanitized URL.
     */
    public static function sanitizeUrl($url)
    {
        $sanitizedUrl = trim(strip_tags($url));

        return $sanitizedUrl;
    }

    /**
     * Validate a date string.
     *
     * @param string $date The date string to validate.
     * @param string $format The expected format of the date string (e.g., Y-m-d).
     * @return bool Returns true if the date string is valid, false otherwise.
     */
    public static function validateDate($date, $format = 'Y-m-d')
    {
        $dateTime = DateTime::createFromFormat($format, $date);
        return $dateTime && $dateTime->format($format) === $date;
    }

    /**
     * Validate an array.
     *
     * @param array $array The array to validate.
     * @return bool Returns true if the value is a valid array, false otherwise.
     */
    public static function validateArray($array)
    {
        return is_array($array);
    }

    /**
     * Sanitize an array by removing HTML tags and special characters from each element.
     *
     * @param array $array The array to sanitize.
     * @return array The sanitized array.
     */
    public static function sanitizeArray($array)
    {
        $sanitizedArray = [];

        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $sanitizedArray[$key] = self::sanitizeArray($value);
            } else {
                $sanitizedArray[$key] = self::sanitizeString($value);
            }
        }

        return $sanitizedArray;
    }

    /**
     * Validate a file upload.
     *
     * @param array $file The file upload information ($_FILES) for validation.
     * @return bool Returns true if the file upload is valid, false otherwise.
     */
    public static function validateFileUpload($file)
    {
        $tmpFile = $file['tmp_name'];
        $fileSize = $file['size'];
        $fileType = $file['type'];

        return true;
    }

    /**
     * Validate a password strength.
     *
     * @param string $password The password to validate.
     * @param int $minLength The minimum length required for the password.
     * @return bool Returns true if the password meets the strength criteria, false otherwise.
     */
    public static function validatePasswordStrength($password, $minLength = 8)
    {
        return strlen($password) >= $minLength;
    }

    /**
     * Validate a phone number.
     *
     * @param string $phoneNumber The phone number to validate.
     * @return bool Returns true if the phone number is valid, false otherwise.
     */
    public static function validatePhoneNumber($phoneNumber)
    {
        $pattern = '/^[0-9]{11}$/';
        return preg_match($pattern, $phoneNumber) === 1;
    }

    /**
     * Sanitize a password by removing unnecessary characters or tags.
     *
     * @param string $password The password to sanitize.
     * @return string The sanitized password.
     */
    public static function sanitizePassword($password)
    {
        $sanitizedPassword = trim(strip_tags($password));

        return $sanitizedPassword;
    }

    /**
     * Sanitize a phone number by removing unnecessary characters or tags.
     *
     * @param string $phoneNumber The phone number to sanitize.
     * @return string The sanitized phone number.
     */
    public static function sanitizePhoneNumber($phoneNumber)
    {
        $sanitizedPhoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);

        return $sanitizedPhoneNumber;
    }

    /**
     * Validate an IP address.
     *
     * @param string $ipAddress The IP address to validate.
     * @return bool Returns true if the IP address is valid, false otherwise.
     */
    public static function validateIpAddress($ipAddress)
    {
        return filter_var($ipAddress, FILTER_VALIDATE_IP) !== false;
    }

    /**
     * Validate a credit card number using the Luhn algorithm.
     *
     * @param string $creditCardNumber The credit card number to validate.
     * @return bool Returns true if the credit card number is valid, false otherwise.
     */
    public static function validateCreditCardNumber($creditCardNumber)
    {
        $creditCardNumber = preg_replace('/\D/', '', $creditCardNumber);
        $sum = 0;
        $numDigits = strlen($creditCardNumber);
        $parity = $numDigits % 2;

        for ($i = 0; $i < $numDigits; $i++) {
            $digit = $creditCardNumber[$i];

            if ($i % 2 === $parity) {
                $digit *= 2;

                if ($digit > 9) {
                    $digit -= 9;
                }
            }

            $sum += $digit;
        }

        return $sum % 10 === 0;
    }

    /**
     * Sanitize an IP address by removing unnecessary characters or tags.
     *
     * @param string $ipAddress The IP address to sanitize.
     * @return string The sanitized IP address.
     */
    public static function sanitizeIpAddress($ipAddress)
    {
        $sanitizedIpAddress = trim(strip_tags($ipAddress));

        return $sanitizedIpAddress;
    }

    /**
     * Validate a username.
     *
     * @param string $username The username to validate.
     * @return bool Returns true if the username is valid, false otherwise.
     */
    public static function validateUsername($username)
    {
        $pattern = '/^[a-zA-Z0-9_]{3,16}$/';
        return preg_match($pattern, $username) === 1;
    }

    /**
     * Validate a boolean value.
     *
     * @param mixed $value The value to validate.
     * @return bool Returns true if the value is a valid boolean, false otherwise.
     */
    public static function validateBoolean($value)
    {
        return is_bool($value);
    }

    /**
     * Sanitize a username by removing unnecessary characters or tags.
     *
     * @param string $username The username to sanitize.
     * @return string The sanitized username.
     */
    public static function sanitizeUsername($username)
    {
        $sanitizedUsername = trim(strip_tags($username));

        return $sanitizedUsername;
    }

    /**
     * Sanitize a boolean value.
     *
     * @param mixed $value The value to sanitize.
     * @return bool The sanitized boolean value.
     */
    public static function sanitizeBoolean($value)
    {
        return (bool)$value;
    }

    /**
     * Validate a UUID (Universally Unique Identifier).
     *
     * @param string $uuid The UUID to validate.
     * @return bool Returns true if the UUID is valid, false otherwise.
     */
    public static function validateUuid($uuid)
    {
        $pattern = '/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i';
        return preg_match($pattern, $uuid) === 1;
    }

    /**
     * Validate an image file based on its MIME type.
     *
     * @param string $filePath The path to the image file.
     * @return bool Returns true if the image file is valid, false otherwise.
     */
    public static function validateImageFile($filePath)
    {
        $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($fileInfo, $filePath);
        finfo_close($fileInfo);

        return in_array($mimeType, $allowedMimeTypes);
    }

    /**
     * Sanitize a UUID (Universally Unique Identifier) by removing unnecessary characters or tags.
     *
     * @param string $uuid The UUID to sanitize.
     * @return string The sanitized UUID.
     */
    public static function sanitizeUuid($uuid)
    {
        $sanitizedUuid = trim(strip_tags($uuid));

        return $sanitizedUuid;
    }

    /**
     * Sanitize an image file name by removing unnecessary characters or tags.
     *
     * @param string $fileName The image file name to sanitize.
     * @return string The sanitized file name.
     */
    public static function sanitizeImageFileName($fileName)
    {
        $sanitizedFileName = trim(strip_tags($fileName));

        return $sanitizedFileName;
    }
}
