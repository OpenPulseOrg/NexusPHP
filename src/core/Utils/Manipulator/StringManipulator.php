<?php

namespace Nxp\Core\Utils\Manipulator;

/**
 * StringManipulator class for performing operations on strings.
 *
 * @package Nxp\Core\Utils\Manipulator
 */
class StringManipulator
{
    /**
     * Reverses a string.
     *
     * @param string $string The input string.
     * @return string The reversed string.
     */
    public static function reverse($string)
    {
        return strrev($string);
    }

    /**
     * Converts a string to uppercase.
     *
     * @param string $string The input string.
     * @return string The uppercase string.
     */
    public static function toUpperCase($string)
    {
        return strtoupper($string);
    }

    /**
     * Converts a string to lowercase.
     *
     * @param string $string The input string.
     * @return string The lowercase string.
     */
    public static function toLowerCase($string)
    {
        return strtolower($string);
    }

    /**
     * Capitalizes the first character of each word in a string.
     *
     * @param string $string The input string.
     * @return string The capitalized string.
     */
    public static function capitalizeWords($string)
    {
        return ucwords($string);
    }

    /**
     * Capitalizes the first letter of a string.
     *
     * @param string $string The input string.
     * @return string The string with the first letter capitalized.
     */
    public static function capitalizeFirstLetter($string)
    {
        return ucfirst($string);
    }

    /**
     * Capitalizes all words in a string.
     *
     * @param string $string The input string.
     * @return string The string with capitalized words.
     */
    public static function capitalizeAllWords($string)
    {
        return ucwords(strtolower($string));
    }

    /**
     * Capitalizes the first letter of each word in a string.
     *
     * @param string $string The input string.
     * @return string The string with capitalized first letters.
     */
    public static function capitalizeFirstLetterOfEachWord($string)
    {
        $words = explode(' ', $string);
        $capitalizedWords = array_map('ucfirst', $words);
        return implode(' ', $capitalizedWords);
    }

    /**
     * Capitalizes the last letter of each word in a string.
     *
     * @param string $string The input string.
     * @return string The string with capitalized last letters.
     */
    public static function capitalizeLastLetterOfEachWord($string)
    {
        $words = explode(' ', $string);
        $capitalizedWords = [];
        foreach ($words as $word) {
            $lastLetter = substr($word, -1);
            $capitalizedWord = substr_replace($word, strtoupper($lastLetter), -1);
            $capitalizedWords[] = $capitalizedWord;
        }
        return implode(' ', $capitalizedWords);
    }

    /**
     * Counts the number of words in a string.
     *
     * @param string $string The input string.
     * @return int The number of words.
     */
    public static function countWords($string)
    {
        return str_word_count($string);
    }

    /**
     * Counts the number of characters in a string.
     *
     * @param string $string The input string.
     * @return int The number of characters.
     */
    public static function countCharacters($string)
    {
        return strlen($string);
    }

    /**
     * Replaces all occurrences of a substring with another string in a string.
     *
     * @param string $string The input string.
     * @param string $search The substring to search for.
     * @param string $replace The replacement string.
     * @return string The resulting string.
     */
    public static function replace($string, $search, $replace)
    {
        return str_replace($search, $replace, $string);
    }

    /**
     * Retrieves a substring from a string.
     *
     * @param string $string The input string.
     * @param int $start The starting position.
     * @param int|null $length The length of the substring (optional).
     * @return string The substring.
     */
    public static function substring($string, $start, $length = null)
    {
        if ($length !== null) {
            return substr($string, $start, $length);
        } else {
            return substr($string, $start);
        }
    }

    /**
     * Trims whitespace from the beginning and end of a string.
     *
     * @param string $string The input string.
     * @return string The trimmed string.
     */
    public static function trim($string)
    {
        return trim($string);
    }

    /**
     * Pads a string to the left with a specific character until it reaches a specified length.
     *
     * @param string $string The input string.
     * @param int $length The desired length.
     * @param string $padding The padding character (optional).
     * @return string The padded string.
     */
    public static function padLeft($string, $length, $padding = ' ')
    {
        return str_pad($string, $length, $padding, STR_PAD_LEFT);
    }

    /**
     * Pads a string to the right with a specific character until it reaches a specified length.
     *
     * @param string $string The input string.
     * @param int $length The desired length.
     * @param string $padding The padding character (optional).
     * @return string The padded string.
     */
    public static function padRight($string, $length, $padding = ' ')
    {
        return str_pad($string, $length, $padding, STR_PAD_RIGHT);
    }

    /**
     * Checks if a string contains a specific substring.
     *
     * @param string $string The input string.
     * @param string $substring The substring to search for.
     * @return bool True if the substring is found, false otherwise.
     */
    public static function contains($string, $substring)
    {
        return strpos($string, $substring) !== false;
    }

    /**
     * Checks if a string starts with a specific substring.
     *
     * @param string $string The input string.
     * @param string $substring The substring to search for.
     * @return bool True if the string starts with the substring, false otherwise.
     */
    public static function startsWith($string, $substring)
    {
        return strpos($string, $substring) === 0;
    }

    /**
     * Checks if a string ends with a specific substring.
     *
     * @param string $string The input string.
     * @param string $substring The substring to search for.
     * @return bool True if the string ends with the substring, false otherwise.
     */
    public static function endsWith($string, $substring)
    {
        $length = strlen($substring);
        if ($length == 0) {
            return true;
        }
        return (substr($string, -$length) === $substring);
    }

    /**
     * Removes whitespace from a string.
     *
     * @param string $string The input string.
     * @return string The string without whitespace.
     */
    public static function removeWhitespace($string)
    {
        return preg_replace('/\s+/', '', $string);
    }

    /**
     * Shuffles the characters in a string.
     *
     * @param string $string The input string.
     * @return string The shuffled string.
     */
    public static function shuffle($string)
    {
        $chars = str_split($string);
        shuffle($chars);
        return implode('', $chars);
    }

    /**
     * Repeats a string a specified number of times.
     *
     * @param string $string The input string.
     * @param int $times The number of times to repeat the string.
     * @return string The repeated string.
     */
    public static function repeat($string, $times)
    {
        return str_repeat($string, $times);
    }

    /**
     * Extracts numbers from a string.
     *
     * @param string $string The input string.
     * @return string The extracted numbers.
     */
    public static function extractNumbers($string)
    {
        preg_match_all('/\d+/', $string, $matches);
        return implode('', $matches[0]);
    }

    /**
     * Masks characters in a string with a specified character and length.
     *
     * @param string $string The input string.
     * @param string $maskCharacter The character used for masking (optional).
     * @param int $maskLength The length of the masked substring (optional).
     * @param bool $maskFromEnd Determines if the substring should be masked from the end (optional).
     * @return string The masked string.
     */
    public static function maskCharacters($string, $maskCharacter = '*', $maskLength = 4, $maskFromEnd = true)
    {
        $length = strlen($string);
        if ($length <= $maskLength) {
            return $string;
        }

        if ($maskFromEnd) {
            $maskedSubstring = substr($string, -$maskLength);
            $maskedString = str_repeat($maskCharacter, $length - $maskLength) . $maskedSubstring;
        } else {
            $maskedSubstring = substr($string, 0, $maskLength);
            $maskedString = $maskedSubstring . str_repeat($maskCharacter, $length - $maskLength);
        }

        return $maskedString;
    }

    /**
     * Checks if a string is a palindrome.
     *
     * @param string $string The input string.
     * @return bool True if the string is a palindrome, false otherwise.
     */
    public static function isPalindrome($string)
    {
        $string = preg_replace('/[^a-zA-Z0-9]/', '', $string);
        $reverse = strrev($string);
        return strtolower($string) === strtolower($reverse);
    }

    /**
     * Counts the number of occurrences of a substring in a string.
     *
     * @param string $string The input string.
     * @param string $substring The substring to search for.
     * @return int The number of occurrences.
     */
    public static function countOccurrences($string, $substring)
    {
        return substr_count($string, $substring);
    }

    /**
     * Inserts a string into another string at a specified position.
     *
     * @param string $string The input string.
     * @param string $insertion The string to insert.
     * @param int $position The position at which to insert the string.
     * @return string The modified string.
     */
    public static function insert($string, $insertion, $position)
    {
        return substr_replace($string, $insertion, $position, 0);
    }

    /**
     * Strips HTML and PHP tags from a string.
     *
     * @param string $string The input string.
     * @return string The string without tags.
     */
    public static function stripTags($string)
    {
        return strip_tags($string);
    }

    /**
     * Truncates a string to a specified length and adds a suffix if necessary.
     *
     * @param string $string The input string.
     * @param int $length The maximum length of the truncated string.
     * @param string $suffix The suffix to add if the string is truncated (optional).
     * @return string The truncated string.
     */
    public static function truncate($string, $length, $suffix = '...')
    {
        if (strlen($string) <= $length) {
            return $string;
        } else {
            return rtrim(substr($string, 0, $length)) . $suffix;
        }
    }

    /**
     * Checks if a string is numeric.
     *
     * @param string $string The input string.
     * @return bool True if the string is numeric, false otherwise.
     */
    public static function isNumeric($string)
    {
        return is_numeric($string);
    }

    /**
     * Converts a string to a slug-like format.
     *
     * @param string $string The input string.
     * @param string $separator The separator to use between words (optional).
     * @return string The slugified string.
     */
    public static function slugify($string, $separator = '-')
    {
        $string = preg_replace('/[^a-zA-Z0-9\s]/', '', $string);
        $string = strtolower(trim($string));
        $string = preg_replace('/\s+/', $separator, $string);
        return $string;
    }

    /**
     * Splits a string into an array using a delimiter.
     *
     * @param string $string The input string.
     * @param string $delimiter The delimiter to use for splitting.
     * @return array The resulting array.
     */
    public static function split($string, $delimiter)
    {
        return explode($delimiter, $string);
    }

    /**
     * Joins the elements of an array into a string using a glue.
     *
     * @param array $array The input array.
     * @param string $glue The glue to use for joining.
     * @return string The resulting string.
     */
    public static function join($array, $glue)
    {
        return implode($glue, $array);
    }

    /**
     * Removes non-alphanumeric characters from a string.
     *
     * @param string $string The input string.
     * @return string The string without non-alphanumeric characters.
     */
    public static function removeNonAlphaNumeric($string)
    {
        return preg_replace('/[^a-zA-Z0-9]/', '', $string);
    }

    /**
     * Reverses the words in a string.
     *
     * @param string $string The input string.
     * @return string The string with reversed words.
     */
    public static function reverseWords($string)
    {
        $words = explode(' ', $string);
        $reversedWords = array_map('strrev', $words);
        return implode(' ', $reversedWords);
    }

    /**
     * Checks if two strings are anagrams.
     *
     * @param string $string1 The first input string.
     * @param string $string2 The second input string.
     * @return bool True if the strings are anagrams, false otherwise.
     */
    public static function isAnagram($string1, $string2)
    {
        $string1 = strtolower(preg_replace('/[^a-zA-Z]/', '', $string1));
        $string2 = strtolower(preg_replace('/[^a-zA-Z]/', '', $string2));
        $string1 = str_split($string1);
        $string2 = str_split($string2);
        sort($string1);
        sort($string2);
        return $string1 === $string2;
    }

    /**
     * Abbreviates a string by taking the first letter of each word.
     *
     * @param string $string The input string.
     * @return string The abbreviated string.
     */
    public static function abbreviate($string)
    {
        $words = preg_split('/\s+/', $string);
        $abbreviated = '';
        foreach ($words as $word) {
            $abbreviated .= $word[0];
        }
        return strtoupper($abbreviated);
    }

    /**
     * Removes duplicate characters from a string.
     *
     * @param string $string The input string.
     * @return string The string without duplicate characters.
     */
    public static function removeDuplicates($string)
    {
        return implode('', array_unique(str_split($string)));
    }

    /**
     * Reverses the case of characters in a string.
     *
     * @param string $string The input string.
     * @return string The string with reversed case.
     */
    public static function reverseCase($string)
    {
        $length = strlen($string);
        $reversed = '';
        for ($i = 0; $i < $length; $i++) {
            $char = $string[$i];
            if (ctype_upper($char)) {
                $reversed .= strtolower($char);
            } elseif (ctype_lower($char)) {
                $reversed .= strtoupper($char);
            } else {
                $reversed .= $char;
            }
        }
        return $reversed;
    }

    /**
     * Converts a string to camel case.
     *
     * @param string $string The input string.
     * @return string The camel cased string.
     */
    public static function camelCase($string)
    {
        $string = ucwords($string, " \t\r\n\f\v-_");
        $string = str_replace(array('-', '_'), '', $string);
        $string = lcfirst($string);
        return $string;
    }

    /**
     * Swaps the case of characters in a string.
     *
     * @param string $string The input string.
     * @return string The string with swapped case.
     */
    public static function swapCase($string)
    {
        return strtr($string, 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz', 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');
    }


    /**
     * Checks if a string is all uppercase.
     *
     * @param string $string The input string.
     * @return bool True if the string is all uppercase, false otherwise.
     */
    public static function isAllUpperCase($string)
    {
        return $string === strtoupper($string);
    }

    /**
     * Checks if a string is all lowercase.
     *
     * @param string $string The input string.
     * @return bool True if the string is all lowercase, false otherwise.
     */
    public static function isAllLowerCase($string)
    {
        return $string === strtolower($string);
    }

    /**
     * Reverses the lines in a string.
     *
     * @param string $string The input string.
     * @return string The string with reversed lines.
     */
    public static function reverseLines($string)
    {
        $lines = explode("\n", $string);
        $reversedLines = array_reverse($lines);
        return implode("\n", $reversedLines);
    }

    /**
     * Counts the number of vowels in a string.
     *
     * @param string $string The input string.
     * @return int The number of vowels.
     */
    public static function countVowels($string)
    {
        $vowels = ['a', 'e', 'i', 'o', 'u'];
        $string = strtolower($string);
        $count = 0;
        foreach ($vowels as $vowel) {
            $count += substr_count($string, $vowel);
        }
        return $count;
    }

    /**
     * Counts the number of consonants in a string.
     *
     * @param string $string The input string.
     * @return int The number of consonants.
     */
    public static function countConsonants($string)
    {
        $string = preg_replace('/[^a-zA-Z]/', '', $string);
        return strlen($string) - self::countVowels($string);
    }

    /**
     * Checks if a string is a substring of another string.
     *
     * @param string $string The input string.
     * @param string $substring The substring to search for.
     * @return bool True if the string is a substring, false otherwise.
     */
    public static function isSubstring($string, $substring)
    {
        return strpos($string, $substring) !== false;
    }

    /**
     * Repeats a character a specified number of times.
     *
     * @param string $character The character to repeat.
     * @param int $times The number of times to repeat the character.
     * @return string The repeated character.
     */
    public static function repeatCharacter($character, $times)
    {
        return str_repeat($character, $times);
    }


    /**
     * Checks if a word is a palindrome.
     *
     * @param string $word The input word.
     * @return bool True if the word is a palindrome, false otherwise.
     */
    public static function isPalindromeWord($word)
    {
        $word = preg_replace('/[^a-zA-Z0-9]/', '', $word);
        $reverse = strrev($word);
        return strtolower($word) === strtolower($reverse);
    }

    /**
     * Counts the number of whitespace characters in a string.
     *
     * @param string $string The input string.
     * @return int The number of whitespace characters.
     */
    public static function countWhitespace($string)
    {
        return substr_count($string, ' ');
    }

    /**
     * Removes the first character from a string.
     *
     * @param string $string The input string.
     * @return string The string without the first character.
     */
    public static function removeFirstCharacter($string)
    {
        return substr($string, 1);
    }

    /**
     * Removes the last character from a string.
     *
     * @param string $string The input string.
     * @return string The string without the last character.
     */
    public static function removeLastCharacter($string)
    {
        return substr($string, 0, -1);
    }

    /**
     * Counts the number of lines in a string.
     *
     * @param string $string The input string.
     * @return int The number of lines.
     */
    public static function countLines($string)
    {
        $lines = explode("\n", $string);
        return count($lines);
    }

    /**
     * Reverses a substring within a string.
     *
     * @param string $string The input string.
     * @param int $start The starting position of the substring.
     * @param int $length The length of the substring.
     * @return string The string with the reversed substring.
     */
    public static function reverseSubstring($string, $start, $length)
    {
        $substring = substr($string, $start, $length);
        $reversedSubstring = strrev($substring);
        return substr_replace($string, $reversedSubstring, $start, $length);
    }

    /**
     * Converts a camel case string to a space-separated string.
     *
     * @param string $string The input string.
     * @return string The converted string.
     */
    public static function camelCaseToSpace($string)
    {
        $pattern = '/(?<!^)[A-Z]/';
        $convertedString = preg_replace($pattern, ' $0', $string);
        return strtolower($convertedString);
    }
}
