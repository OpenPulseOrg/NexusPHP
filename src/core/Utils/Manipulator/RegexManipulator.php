<?php

namespace Nxp\Core\Utils\Manipulator;

/**
 * RegexManipulator class for performing operations using regular expressions.
 *
 * @package Nxp\Core\Utils\Manipulator
 */
class RegexManipulator
{
    /**
     * Match a pattern in a string.
     *
     * @param string $pattern The regular expression pattern.
     * @param string $subject The subject string to match against.
     * @param int $flags Optional flags to modify the behavior of the matching operation.
     * @param int $offset Optional starting offset of the search.
     * @return bool True if the pattern matches, false otherwise.
     */
    public static function match($pattern, $subject, $flags = 0, $offset = 0)
    {
        return preg_match($pattern, $subject, $matches, $flags, $offset) === 1;
    }

    /**
     * Perform a global regular expression match.
     *
     * @param string $pattern The regular expression pattern.
     * @param string $subject The subject string to match against.
     * @param int $flags Optional flags to modify the behavior of the matching operation.
     * @param int $offset Optional starting offset of the search.
     * @return array|null An array of matches or null if no matches were found.
     */
    public static function matchAll($pattern, $subject, $flags = PREG_PATTERN_ORDER, $offset = 0)
    {
        preg_match_all($pattern, $subject, $matches, $flags, $offset);
        return $matches ?: null;
    }

    /**
     * Replace a pattern with a given replacement in a string.
     *
     * @param string|array $pattern The regular expression pattern(s) to search for.
     * @param string|array $replacement The replacement string(s).
     * @param string|array $subject The subject string(s) to search within.
     * @param int $limit Optional maximum number of replacements to perform.
     * @param int $count The number of replacements made.
     * @return string|array The subject string(s) with replacements made.
     */
    public static function replace($pattern, $replacement, $subject, $limit = -1, &$count = null)
    {
        return preg_replace($pattern, $replacement, $subject, $limit, $count);
    }

    /**
     * Extract matches for a given pattern in a string.
     *
     * @param string $pattern The regular expression pattern.
     * @param string $subject The subject string to match against.
     * @return array|null An array of matches or null if no matches were found.
     */
    public static function extract($pattern, $subject)
    {
        preg_match($pattern, $subject, $matches);
        return $matches ?: null;
    }

    /**
     * Validate input against a specific pattern.
     *
     * @param string $pattern The regular expression pattern.
     * @param string $input The input string to validate.
     * @return bool True if the input matches the pattern, false otherwise.
     */
    public static function validate($pattern, $input)
    {
        return preg_match($pattern, $input) === 1;
    }

    /**
     * Split a string into an array of substrings based on a regular expression pattern.
     *
     * @param string $pattern The regular expression pattern.
     * @param string $subject The subject string to split.
     * @param int $limit Optional maximum number of splits.
     * @param int $flags Optional flags to modify the behavior of the split operation.
     * @return array|false An array of substrings resulting from the split or false on failure.
     */
    public static function split($pattern, $subject, $limit = -1, $flags = 0)
    {
        return preg_split($pattern, $subject, $limit, $flags);
    }

    /**
     * Quote regular expression characters in a string.
     *
     * @param string $string The string to quote.
     * @return string The quoted string.
     */
    public static function quote($string)
    {
        return preg_quote($string);
    }

    /**
     * Get the last error message from the last regular expression operation.
     *
     * @return string|null The error message or null if there was no error.
     */
    public static function getLastError()
    {
        return preg_last_error_msg();
    }

    /**
     * Check if a pattern exists in a string.
     *
     * @param string $pattern The regular expression pattern.
     * @param string $subject The subject string to search in.
     * @return bool True if the pattern exists, false otherwise.
     */
    public static function contains($pattern, $subject)
    {
        return preg_match($pattern, $subject) === 1;
    }

    /**
     * Count the number of matches for a pattern in a string.
     *
     * @param string $pattern The regular expression pattern.
     * @param string $subject The subject string to search in.
     * @return int The number of matches.
     */
    public static function countMatches($pattern, $subject)
    {
        return preg_match_all($pattern, $subject);
    }

    /**
     * Remove matches for a pattern from a string.
     *
     * @param string $pattern The regular expression pattern.
     * @param string $subject The subject string to remove matches from.
     * @param int $limit Optional maximum number of removals to perform.
     * @return string The subject string with matches removed.
     */
    public static function removeMatches($pattern, $subject, $limit = -1)
    {
        return preg_replace($pattern, '', $subject, $limit);
    }

    /**
     * Get the matched groups for a pattern in a string.
     *
     * @param string $pattern The regular expression pattern.
     * @param string $subject The subject string to match against.
     * @return array|null An array of matched groups or null if no matches were found.
     */
    public static function getGroups($pattern, $subject)
    {
        if (preg_match($pattern, $subject, $matches) === 1) {
            return array_slice($matches, 1);
        }
        return null;
    }

    /**
     * Extract a specific group from the match of a pattern in a string.
     *
     * @param string $pattern The regular expression pattern.
     * @param string $subject The subject string to match against.
     * @param int $groupIndex The index of the group to extract.
     * @return string|null The extracted group or null if the group index is invalid or no matches were found.
     */
    public static function extractGroup($pattern, $subject, $groupIndex)
    {
        if (preg_match($pattern, $subject, $matches) === 1 && isset($matches[$groupIndex])) {
            return $matches[$groupIndex];
        }
        return null;
    }
}
