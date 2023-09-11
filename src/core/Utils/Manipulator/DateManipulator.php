<?php

namespace Nxp\Core\Utils\Manipulator;

use DateTime;

/**
 * DateManipulator class for performing various operations on dates and times.
 *
 * @package Nxp\Core\Utils\Manipulator
 */
class DateManipulator
{

    /**
     * Gets the current date and time.
     *
     * @param string|null $format The desired format (optional).
     * @return string The current date and time (formatted as specified).
     */
    public static function getCurrentDateTime($format = 'Y-m-d H:i:s')
    {
        $dateTime = new DateTime();
        return $dateTime->format($format);
    }

    /**
     * Gets the current date.
     *
     * @param string|null $format The desired format (optional).
     * @return string The current date (formatted as specified).
     */
    public static function getCurrentDate($format = 'Y-m-d')
    {
        $dateTime = new DateTime();
        return $dateTime->format($format);
    }
    
    /**
     * Adds a specified number of days to the given date.
     *
     * @param string $date The input date (in format 'Y-m-d').
     * @param int $days The number of days to add.
     * @return string The resulting date (in format 'Y-m-d').
     */
    public static function addDays($date, $days)
    {
        $dateTime = new DateTime($date);
        $dateTime->modify("+$days days");
        return $dateTime->format('Y-m-d');
    }

    /**
     * Subtracts a specified number of days from the given date.
     *
     * @param string $date The input date (in format 'Y-m-d').
     * @param int $days The number of days to subtract.
     * @return string The resulting date (in format 'Y-m-d').
     */
    public static function subtractDays($date, $days)
    {
        $dateTime = new DateTime($date);
        $dateTime->modify("-$days days");
        return $dateTime->format('Y-m-d');
    }

    /**
     * Calculates the difference in days between two dates.
     *
     * @param string $date1 The first date (in format 'Y-m-d').
     * @param string $date2 The second date (in format 'Y-m-d').
     * @return int The number of days between the two dates.
     */
    public static function differenceInDays($date1, $date2)
    {
        $dateTime1 = new DateTime($date1);
        $dateTime2 = new DateTime($date2);
        $interval = $dateTime1->diff($dateTime2);
        return (int)$interval->format('%r%a');
    }

    /**
     * Adds a specified number of months to the given date.
     *
     * @param string $date The input date (in format 'Y-m-d').
     * @param int $months The number of months to add.
     * @return string The resulting date (in format 'Y-m-d').
     */
    public static function addMonths($date, $months)
    {
        $dateTime = new DateTime($date);
        $dateTime->modify("+$months months");
        return $dateTime->format('Y-m-d');
    }

    /**
     * Subtracts a specified number of months from the given date.
     *
     * @param string $date The input date (in format 'Y-m-d').
     * @param int $months The number of months to subtract.
     * @return string The resulting date (in format 'Y-m-d').
     */
    public static function subtractMonths($date, $months)
    {
        $dateTime = new DateTime($date);
        $dateTime->modify("-$months months");
        return $dateTime->format('Y-m-d');
    }

    /**
     * Adds a specified number of years to the given date.
     *
     * @param string $date The input date (in format 'Y-m-d').
     * @param int $years The number of years to add.
     * @return string The resulting date (in format 'Y-m-d').
     */
    public static function addYears($date, $years)
    {
        $dateTime = new DateTime($date);
        $dateTime->modify("+$years years");
        return $dateTime->format('Y-m-d');
    }

    /**
     * Subtracts a specified number of years from the given date.
     *
     * @param string $date The input date (in format 'Y-m-d').
     * @param int $years The number of years to subtract.
     * @return string The resulting date (in format 'Y-m-d').
     */
    public static function subtractYears($date, $years)
    {
        $dateTime = new DateTime($date);
        $dateTime->modify("-$years years");
        return $dateTime->format('Y-m-d');
    }

    /**
     * Returns the day of the week for a given date.
     *
     * @param string $date The input date (in format 'Y-m-d').
     * @return string The day of the week (e.g., 'Monday', 'Tuesday', etc.).
     */
    public static function getDayOfWeek($date)
    {
        $dateTime = new DateTime($date);
        return $dateTime->format('l');
    }

    /**
     * Returns the number of days in a given month.
     *
     * @param int $month The month (1-12).
     * @param int $year The year.
     * @return int The number of days in the month.
     */
    public static function getDaysInMonth($month, $year)
    {
        return cal_days_in_month(CAL_GREGORIAN, $month, $year);
    }

    /**
     * Checks if a given year is a leap year.
     *
     * @param int $year The year.
     * @return bool True if it's a leap year, false otherwise.
     */
    public static function isLeapYear($year)
    {
        return ($year % 4 === 0 && $year % 100 !== 0) || ($year % 400 === 0);
    }

    /**
     * Formats a given date to a different format.
     *
     * @param string $date The input date (in format 'Y-m-d').
     * @param string $format The desired format (e.g., 'd/m/Y', 'Y-m-d H:i:s', etc.).
     * @return string The formatted date.
     */
    public static function formatDate($date, $format)
    {
        $dateTime = new DateTime($date);
        return $dateTime->format($format);
    }

    /**
     * Calculates the age based on a given birthdate.
     *
     * @param string $birthdate The birthdate (in format 'Y-m-d').
     * @return int The age in years.
     */
    public static function calculateAge($birthdate)
    {
        $today = new DateTime();
        $birthDate = new DateTime($birthdate);
        $ageInterval = $today->diff($birthDate);
        return $ageInterval->y;
    }

    /**
     * Checks if a given date is in the future.
     *
     * @param string $date The input date (in format 'Y-m-d').
     * @return bool True if the date is in the future, false otherwise.
     */
    public static function isFutureDate($date)
    {
        $today = new DateTime();
        $dateTime = new DateTime($date);
        return $dateTime > $today;
    }

    /**
     * Checks if a given date is in the past.
     *
     * @param string $date The input date (in format 'Y-m-d').
     * @return bool True if the date is in the past, false otherwise.
     */
    public static function isPastDate($date)
    {
        $today = new DateTime();
        $dateTime = new DateTime($date);
        return $dateTime < $today;
    }


    /**
     * Adds a specified number of hours to the given date and time.
     *
     * @param string $dateTime The input date and time (in format 'Y-m-d H:i:s').
     * @param int $hours The number of hours to add.
     * @return string The resulting date and time (in format 'Y-m-d H:i:s').
     */
    public static function addHours($dateTime, $hours)
    {
        $dateTimeObj = new DateTime($dateTime);
        $dateTimeObj->modify("+$hours hours");
        return $dateTimeObj->format('Y-m-d H:i:s');
    }

    /**
     * Subtracts a specified number of hours from the given date and time.
     *
     * @param string $dateTime The input date and time (in format 'Y-m-d H:i:s').
     * @param int $hours The number of hours to subtract.
     * @return string The resulting date and time (in format 'Y-m-d H:i:s').
     */
    public static function subtractHours($dateTime, $hours)
    {
        $dateTimeObj = new DateTime($dateTime);
        $dateTimeObj->modify("-$hours hours");
        return $dateTimeObj->format('Y-m-d H:i:s');
    }

    /**
     * Calculates the difference in hours between two dates and times.
     *
     * @param string $dateTime1 The first date and time (in format 'Y-m-d H:i:s').
     * @param string $dateTime2 The second date and time (in format 'Y-m-d H:i:s').
     * @return int The number of hours between the two dates and times.
     */
    public static function differenceInHours($dateTime1, $dateTime2)
    {
        $dateTimeObj1 = new DateTime($dateTime1);
        $dateTimeObj2 = new DateTime($dateTime2);
        $interval = $dateTimeObj1->diff($dateTimeObj2);
        $hours = ($interval->days * 24) + $interval->h;
        return $hours;
    }

    /**
     * Adds a specified number of minutes to the given date and time.
     *
     * @param string $dateTime The input date and time (in format 'Y-m-d H:i:s').
     * @param int $minutes The number of minutes to add.
     * @return string The resulting date and time (in format 'Y-m-d H:i:s').
     */
    public static function addMinutes($dateTime, $minutes)
    {
        $dateTimeObj = new DateTime($dateTime);
        $dateTimeObj->modify("+$minutes minutes");
        return $dateTimeObj->format('Y-m-d H:i:s');
    }

    /**
     * Subtracts a specified number of minutes from the given date and time.
     *
     * @param string $dateTime The input date and time (in format 'Y-m-d H:i:s').
     * @param int $minutes The number of minutes to subtract.
     * @return string The resulting date and time (in format 'Y-m-d H:i:s').
     */
    public static function subtractMinutes($dateTime, $minutes)
    {
        $dateTimeObj = new DateTime($dateTime);
        $dateTimeObj->modify("-$minutes minutes");
        return $dateTimeObj->format('Y-m-d H:i:s');
    }

    /**
     * Calculates the difference in minutes between two dates and times.
     *
     * @param string $dateTime1 The first date and time (in format 'Y-m-d H:i:s').
     * @param string $dateTime2 The second date and time (in format 'Y-m-d H:i:s').
     * @return int The number of minutes between the two dates and times.
     */
    public static function differenceInMinutes($dateTime1, $dateTime2)
    {
        $dateTimeObj1 = new DateTime($dateTime1);
        $dateTimeObj2 = new DateTime($dateTime2);
        $interval = $dateTimeObj1->diff($dateTimeObj2);
        $minutes = ($interval->days * 24 * 60) + ($interval->h * 60) + $interval->i;
        return $minutes;
    }

    /**
     * Adds a specified number of seconds to the given date and time.
     *
     * @param string $dateTime The input date and time (in format 'Y-m-d H:i:s').
     * @param int $seconds The number of seconds to add.
     * @return string The resulting date and time (in format 'Y-m-d H:i:s').
     */
    public static function addSeconds($dateTime, $seconds)
    {
        $dateTimeObj = new DateTime($dateTime);
        $dateTimeObj->modify("+$seconds seconds");
        return $dateTimeObj->format('Y-m-d H:i:s');
    }

    /**
     * Subtracts a specified number of seconds from the given date and time.
     *
     * @param string $dateTime The input date and time (in format 'Y-m-d H:i:s').
     * @param int $seconds The number of seconds to subtract.
     * @return string The resulting date and time (in format 'Y-m-d H:i:s').
     */
    public static function subtractSeconds($dateTime, $seconds)
    {
        $dateTimeObj = new DateTime($dateTime);
        $dateTimeObj->modify("-$seconds seconds");
        return $dateTimeObj->format('Y-m-d H:i:s');
    }

    /**
     * Calculates the difference in seconds between two dates and times.
     *
     * @param string $dateTime1 The first date and time (in format 'Y-m-d H:i:s').
     * @param string $dateTime2 The second date and time (in format 'Y-m-d H:i:s').
     * @return int The number of seconds between the two dates and times.
     */
    public static function differenceInSeconds($dateTime1, $dateTime2)
    {
        $dateTimeObj1 = new DateTime($dateTime1);
        $dateTimeObj2 = new DateTime($dateTime2);
        $interval = $dateTimeObj1->diff($dateTimeObj2);
        $seconds = ($interval->days * 24 * 60 * 60) + ($interval->h * 60 * 60) + ($interval->i * 60) + $interval->s;
        return $seconds;
    }

    /**
     * Calculates the elapsed time from a given date and time string.
     *
     * @param string $dateTime The input date and time (in format 'Y-m-d H:i:s').
     * @return string The elapsed time in a human-readable format (e.g., '2 hours ago', '3 days ago', etc.).
     */
    public static function getElapsedTime($dateTime)
    {
        $currentTime = new DateTime();
        $dateTimeObj = new DateTime($dateTime);
        $interval = $dateTimeObj->diff($currentTime);

        $elapsedTime = '';
        if ($interval->y > 0) {
            $elapsedTime = $interval->y . ' year' . ($interval->y > 1 ? 's' : '') . ' ago';
        } elseif ($interval->m > 0) {
            $elapsedTime = $interval->m . ' month' . ($interval->m > 1 ? 's' : '') . ' ago';
        } elseif ($interval->d > 0) {
            $elapsedTime = $interval->d . ' day' . ($interval->d > 1 ? 's' : '') . ' ago';
        } elseif ($interval->h > 0) {
            $elapsedTime = $interval->h . ' hour' . ($interval->h > 1 ? 's' : '') . ' ago';
        } elseif ($interval->i > 0) {
            $elapsedTime = $interval->i . ' minute' . ($interval->i > 1 ? 's' : '') . ' ago';
        } else {
            $elapsedTime = $interval->s . ' second' . ($interval->s > 1 ? 's' : '') . ' ago';
        }

        return $elapsedTime;
    }
}
