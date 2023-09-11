<?php

namespace Nxp\Core\Utils\Randomization;

/**
 * Generator class for generating random values and data.
 *
 * @package Nxp\Core\Utils\Randomization
 */
class Generator
{
    /**
     * Generate a UUID (Universally Unique Identifier) using either the com_create_guid() function or the openssl_random_pseudo_bytes() function.
     *
     * @return string Generated UUID without braces
     */
    public static function generateUUID()
    {
        if (function_exists('com_create_guid') === true) {
            return trim(com_create_guid(), '{}');
        }

        $data = openssl_random_pseudo_bytes(16);
        assert(strlen($data) == 16);

        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    /**
     * Generate a random string of specified length using a list of characters.
     *
     * @param int $length The length of the string to generate
     * @return string The generated random string
     */
    public static function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';

        // Loop through the specified length and append a random character from the list of characters to the string.
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }

        return $randomString;
    }

    /**
     * Generate a random integer of specified length using a list of digits.
     *
     * @param int $length The length of the integer to generate
     * @return string The generated random integer
     */
    public static function generateRandomInt($length = 10)
    {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomInt = '';

        // Loop through the specified length and append a random digit from the list of digits to the integer.
        for ($i = 0; $i < $length; $i++) {
            $randomInt .= $characters[random_int(0, $charactersLength - 1)];
        }

        return $randomInt;
    }


    /**
     * Generate a random float number within a range
     *
     * @param float $min Minimum value of the range (default: 0)
     * @param float $max Maximum value of the range (default: 1)
     * @return float Random float number within the given range
     */
    public static function generateFloat($min = 0, $max = 1)
    {
        return $min + mt_rand() / mt_getrandmax() * ($max - $min);
    }

    /**
     * Generate a random boolean value
     *
     * @return bool Random boolean value
     */
    public static function generateBoolean()
    {
        return mt_rand(0, 1) === 1;
    }

    /**
     * Generate a random value from an array
     *
     * @param array $array Input array
     * @return mixed Random value from the input array
     */
    public static function generateFromArray($array)
    {
        $index = mt_rand(0, count($array) - 1);
        return $array[$index];
    }

    /**
     * Generate a random phone number in the format (###) ###-####
     *
     * @return string Random phone number
     */
    public static function generatePhoneNumber()
    {
        $areaCode = mt_rand(200, 999);
        $exchangeCode = mt_rand(200, 999);
        $lineNumber = mt_rand(1000, 9999);
        return sprintf('(%03d) %03d-%04d', $areaCode, $exchangeCode, $lineNumber);
    }

    /**
     * Generate a random date within a given range
     *
     * @param string $start Start date in Y-m-d format
     * @param string $end End date in Y-m-d format
     * @return string Random date in Y-m-d format
     */
    public static function generateDate($start, $end)
    {
        $timestampStart = strtotime($start);
        $timestampEnd = strtotime($end);
        $randomTimestamp = mt_rand($timestampStart, $timestampEnd);
        return date('Y-m-d', $randomTimestamp);
    }

    /**
     * Generate a random time between two given timestamps
     * 
     * @param string $start The starting timestamp in "Y-m-d H:i:s" format
     * @param string $end The ending timestamp in "Y-m-d H:i:s" format
     * 
     * @return string The randomly generated time in "H:i:s" format
     */
    public static function generateTime($start, $end)
    {
        $timestampStart = strtotime($start);
        $timestampEnd = strtotime($end);
        $randomTimestamp = mt_rand($timestampStart, $timestampEnd);
        return date('H:i:s', $randomTimestamp);
    }

    /**
     * Generate a random datetime between two given timestamps
     * 
     * @param string $start The starting timestamp in "Y-m-d H:i:s" format
     * @param string $end The ending timestamp in "Y-m-d H:i:s" format
     * 
     * @return string The randomly generated datetime in "Y-m-d H:i:s" format
     */
    public static function generateDateTime($start, $end)
    {
        $timestampStart = strtotime($start);
        $timestampEnd = strtotime($end);
        $randomTimestamp = mt_rand($timestampStart, $timestampEnd);
        return date('Y-m-d H:i:s', $randomTimestamp);
    }

    /**
     * Generate a random IPv4 address
     * 
     * @return string The randomly generated IPv4 address in dotted decimal notation
     */
    public static function generateIpAddress()
    {
        return long2ip(mt_rand());
    }

    /**
     * Generate a random MAC address
     * 
     * @return string The randomly generated MAC address in colon-separated hexadecimal format
     */
    public static function generateMacAddress()
    {
        $macAddress = '';
        for ($i = 0; $i < 6; $i++) {
            $macAddress .= sprintf('%02X', mt_rand(0, 255));
            if ($i < 5) {
                $macAddress .= ':';
            }
        }
        return $macAddress;
    }

    /**
     * Generate a random password with a specified length.
     *
     * @param int $length The length of the password to generate. Default is 8.
     *
     * @return string The generated password.
     */
    public static function generatePassword($length = 8)
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!£$%&*';
        $password = '';
        $charsLength = strlen($chars);

        for ($i = 0; $i < $length; $i++) {
            // Generate a random index within the bounds of $chars
            $randomIndex = mt_rand(0, $charsLength - 1);
            $encodedChar = htmlspecialchars($chars[$randomIndex], ENT_QUOTES, 'UTF-8');
            $password .= $encodedChar;
        }

        return $password;
    }


    /**
     * Generate a random file extension from a list of available extensions.
     *
     * @return string The generated file extension.
     */
    public static function generateFileExtension()
    {
        $extensions = array('jpg', 'png', 'gif', 'pdf', 'doc', 'xls');
        return $extensions[mt_rand(0, count($extensions) - 1)];
    }

    /**
     * Generate random coordinates within a specified range.
     *
     * @param float $min_lat The minimum latitude value. Default is -90.
     * @param float $max_lat The maximum latitude value. Default is 90.
     * @param float $min_lon The minimum longitude value. Default is -180.
     * @param float $max_lon The maximum longitude value. Default is 180.
     *
     * @return array An array containing the generated latitude and longitude coordinates.
     */
    public static function generateRandomCoordinates($min_lat = -90, $max_lat = 90, $min_lon = -180, $max_lon = 180)
    {
        $lat = mt_rand($min_lat * 1000000, $max_lat * 1000000) / 1000000;
        $lon = mt_rand($min_lon * 1000000, $max_lon * 1000000) / 1000000;
        return array('latitude' => $lat, 'longitude' => $lon);
    }

    /**
     * Generate a random hexadecimal color code.
     *
     * @return string The generated color code in hexadecimal format.
     */
    public static function generateRandomColor()
    {
        return sprintf('#%06X', mt_rand(0, 0xFFFFFF));
    }
}
