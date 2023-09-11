<?php

namespace Nxp\Core\Security\Cleaning;

use DateTime;
use Nxp\Core\Utils\Session\Manager as SessionManager;

class Validator
{
    private $errors = [];

    private $sessionManager;

    public function __construct()
    {
        $this->sessionManager = SessionManager::getInstance();
        $this->sessionManager->start();
    }

    public function getErrors()
    {
        $errors = $this->sessionManager->get('validation_errors', []);
        $this->sessionManager->delete('validation_errors'); // Clear errors from session after retrieving them, with empty segment
        return $errors;
    }


    private function addError($error)
    {
        $errors = $this->getErrors();
        $errors[] = $error;
        $this->sessionManager->set('validation_errors', $errors);
    }


    public function isValid()
    {
        return empty($this->getErrors());
    }

    public function validateEmail($email)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return Sanitizer::sanitizeEmail($email);
        } else {
            $this->addError("Invalid email format");
            return false;
        }
    }

    public function validateStringLength($string, $min, $max)
    {
        $length = strlen($string);
        if ($length < $min) {
            $this->addError("String is too short. Minimum length is $min.");
            return false;
        }
        if ($length > $max) {
            $this->addError("String is too long. Maximum length is $max.");
            return false;
        }
        return Sanitizer::sanitizeString($string);
    }

    public function validateNumberRange($number, $min, $max)
    {
        if ($number < $min) {
            $this->addError("Number is too small. Minimum value is $min.");
            return false;
        }
        if ($number > $max) {
            $this->addError("Number is too large. Maximum value is $max.");
            return false;
        }
        // Assuming the number can be either float or int
        return is_float($number) ? Sanitizer::sanitizeFloat($number) : Sanitizer::sanitizeInt($number);
    }

    public function validateUrl($url)
    {
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            return Sanitizer::sanitizeURL($url);
        } else {
            $this->addError("Invalid URL format");
            return false;
        }
    }

    public function validateNoWhitespace($string)
    {
        if (preg_match('/^\s|\s$/', $string)) {
            $this->addError("The string should not have whitespace at the beginning or end.");
            return false;
        }
        return true;
    }


    public function validatePhone($phone)
    {
        $pattern = "/^(\+?1? ?(\d{1,4})?[-. ]?)?(\()?(\d{1,3})(?(3)\))[-. ]?(\d{1,4})[-. ]?(\d{1,4})$/";
        if (preg_match($pattern, $phone)) {
            return true;
        } else {
            $this->addError("Invalid phone number");
            return false;
        }
    }

    public function validateDate($date, $format = 'Y-m-d')
    {
        $d = DateTime::createFromFormat($format, $date);
        if ($d && $d->format($format) == $date) {
            return true;
        } else {
            $this->addError("Invalid date format");
            return false;
        }
    }

    public function validateAlphaNumeric($string)
    {
        if (ctype_alnum($string)) {
            return true;
        } else {
            $this->addError("The string should be alphanumeric");
            return false;
        }
    }

    public function validateAlpha($string)
    {
        if (ctype_alpha($string)) {
            return true;
        } else {
            $this->addError("The string should contain only alphabets");
            return false;
        }
    }

    public function containsSpecialCharacter($string, $requiredCount = 1)
    {
        // This pattern matches special characters.
        $pattern = "/[^a-zA-Z0-9\\s]/";

        // Count occurrences of special characters in the string.
        $matches = [];
        preg_match_all($pattern, $string, $matches);
        $specialCharCount = count($matches[0]);

        if ($specialCharCount >= $requiredCount) {
            return true;
        } else {
            $this->addError("The string should contain at least {$requiredCount} special character(s). Found {$specialCharCount}.");
            return false;
        }
    }

    public function isNumeric($string)
    {
        if (!is_numeric($string)) {
            $this->addError("The input should be numeric.");
            return false;
        }
        return true;
    }

    public function validateIPAddress($ip)
    {
        if (!filter_var($ip, FILTER_VALIDATE_IP)) {
            $this->addError("Invalid IP address.");
            return false;
        }
        return true;
    }
    public function validateCurrency($amount)
    {
        if (!preg_match('/^\$?(\d{1,3},(\d{3},)*\d{3}|(\d+))(\.\d{2})?$/', $amount)) {
            $this->addError("Invalid currency format.");
            return false;
        }
        return true;
    }

    public function validateHexColor($color)
    {
        if (!preg_match('/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/', $color)) {
            $this->addError("Invalid hex color format.");
            return false;
        }
        return true;
    }

    public function validateCreditCard($number)
    {
        $number = preg_replace('/\D/', '', $number);
        $sum = 0;
        $alt = false;
        for ($i = strlen($number) - 1; $i >= 0; $i--) {
            if ($alt) {
                $temp = $number[$i] * 2;
                $sum += ($temp > 9) ? $temp - 9 : $temp;
            } else {
                $sum += $number[$i];
            }
            $alt = !$alt;
        }
        if ($sum % 10 == 0) {
            return true;
        } else {
            $this->addError("Invalid credit card number.");
            return false;
        }
    }

    public function validateTime($time)
    {
        if (!preg_match('/^(2[0-3]|[01]?[0-9]):([0-5]?[0-9])$/', $time)) {
            $this->addError("Invalid time format (should be HH:MM in 24-hour format).");
            return false;
        }
        return true;
    }

    public function validateAgeRange($birthdate, $minAge, $maxAge)
    {
        $today = new DateTime();
        $birth = new DateTime($birthdate);
        $age = $today->diff($birth)->y;
        if ($age < $minAge || $age > $maxAge) {
            $this->addError("Age is out of the acceptable range ({$minAge}-{$maxAge}).");
            return false;
        }
        return true;
    }

    public function areEqual($input1, $input2)
    {
        if ($input1 !== $input2) {
            $this->addError("The inputs do not match.");
            return false;
        }
        return true;
    }

    public function validatePostalCode($postal, $countryCode = 'uk', $customPattern = null)
    {
        $patterns = [
            'us' => '/^\d{5}(-\d{4})?$/',
            'uk' => '/^(GIR 0AA|[A-PR-UWYZ]([0-9]{1,2}|([A-HK-Y][0-9]([0-9ABEHMNPRV-Y])?)?) [0-9][ABD-HJLNP-UW-Z]{2})$/',
            'ca' => '/^[A-Z][0-9][A-Z] ?[0-9][A-Z][0-9]$/i',
            'fr' => '/^\d{5}$/',
            'de' => '/^\d{5}$/',
            'au' => '/^\d{4}$/',
            'it' => '/^\d{5}$/',
            'es' => '/^\d{5}$/',
            'nl' => '/^\d{4} ?[A-Z]{2}$/i',
            'be' => '/^\d{4}$/',
            'dk' => '/^\d{4}$/',
            'se' => '/^\d{3} ?\d{2}$/',
            'no' => '/^\d{4}$/',
            'br' => '/^\d{5}-\d{3}$/',
            'ar' => '/^[A-HJ-NP-Z]{1}\d{4}[A-Z]{3}$/',
            'in' => '/^\d{6}$/',
            'jp' => '/^\d{3}-\d{4}$/',
            'ru' => '/^\d{6}$/',
            'za' => '/^\d{4}$/',
            'ch' => '/^\d{4}$/',
        ];

        // Use custom pattern if provided
        $pattern = $customPattern ? $customPattern : (isset($patterns[$countryCode]) ? $patterns[$countryCode] : null);

        if (!$pattern) {
            $this->addError("Unsupported country code for postal validation and no custom pattern provided.");
            return false;
        }

        if (!preg_match($pattern, $postal)) {
            $this->addError("Invalid postal code format for {$countryCode}.");
            return false;
        }

        return true;
    }
}
