<?php

namespace Nxp\Core\Utils\Math;

use InvalidArgumentException;

/**
 * Math class for performing various mathematical calculations.
 *
 * @package Nxp\Core\Utils\Math
 */
class Math
{
    /**
     * Calculates the average of an array of numbers.
     *
     * @param array $numbers An array of numbers.
     * @return float The average value.
     */
    public static function calculateAverage(array $numbers): float
    {
        $sum = array_sum($numbers);
        $count = count($numbers);
        return $sum / $count;
    }

    /**
     * Generates a random number within a specified range.
     *
     * @param int $min The minimum value.
     * @param int $max The maximum value.
     * @return int The generated random number.
     */
    public static function generateRandomNumber(int $min, int $max): int
    {
        return rand($min, $max);
    }

    /**
     * Rounds a number to a specified number of decimal places.
     *
     * @param float $number The number to round.
     * @param int $decimals The number of decimal places.
     * @return float The rounded number.
     */
    public static function roundNumber(float $number, int $decimals): float
    {
        return round($number, $decimals);
    }

    /**
     * Converts a decimal number to binary.
     *
     * @param int $number The decimal number.
     * @return string The binary representation of the number.
     */
    public static function decimalToBinary(int $number): string
    {
        return decbin($number);
    }

    /**
     * Converts a binary number to decimal.
     *
     * @param string $number The binary number.
     * @return int The decimal representation of the number.
     */
    public static function binaryToDecimal(string $number): int
    {
        return bindec($number);
    }

    /**
     * Solves a quadratic equation of the form ax^2 + bx + c = 0.
     *
     * @param float $a The coefficient of x^2.
     * @param float $b The coefficient of x.
     * @param float $c The constant term.
     * @return array An array containing the real solutions.
     */
    public static function solveQuadraticEquation(float $a, float $b, float $c): array
    {
        $delta = $b * $b - 4 * $a * $c;

        if ($delta < 0) {
            return []; // No real solutions
        } elseif ($delta === 0) {
            $x = -$b / (2 * $a);
            return [$x]; // One real solution
        } else {
            $x1 = (-$b + sqrt($delta)) / (2 * $a);
            $x2 = (-$b - sqrt($delta)) / (2 * $a);
            return [$x1, $x2]; // Two real solutions
        }
    }

    /**
     * Calculates the factorial of a number.
     *
     * @param int $number The number.
     * @return int The factorial of the number.
     * @throws InvalidArgumentException If the number is negative.
     */
    public static function calculateFactorial(int $number): int
    {
        if ($number < 0) {
            throw new InvalidArgumentException("Number must be non-negative.");
        }

        if ($number === 0 || $number === 1) {
            return 1;
        }

        $factorial = 1;
        for ($i = 2; $i <= $number; $i++) {
            $factorial *= $i;
        }

        return $factorial;
    }

    /**
     * Calculates the power of a number.
     *
     * @param float $base The base number.
     * @param float $exponent The exponent.
     * @return float The result of the power operation.
     */
    public static function calculatePower(float $base, float $exponent): float
    {
        return pow($base, $exponent);
    }

    /**
     * Calculates the square root of a number.
     *
     * @param float $number The number.
     * @return float The square root of the number.
     */
    public static function calculateSquareRoot(float $number): float
    {
        return sqrt($number);
    }

    /**
     * Calculates the absolute value of a number.
     *
     * @param float $number The number.
     * @return float The absolute value of the number.
     */
    public static function calculateAbsoluteValue(float $number): float
    {
        return abs($number);
    }

    /**
     * Calculates the natural logarithm of a number.
     *
     * @param float $number The number.
     * @return float The natural logarithm of the number.
     */
    public static function calculateNaturalLogarithm(float $number): float
    {
        return log($number);
    }

    /**
     * Calculates the greatest common divisor (GCD) of two numbers.
     *
     * @param int $a The first number.
     * @param int $b The second number.
     * @return int The GCD of the two numbers.
     */
    public static function calculateGCD(int $a, int $b): int
    {
        while ($b !== 0) {
            $temp = $a;
            $a = $b;
            $b = $temp % $b;
        }

        return abs($a);
    }

    /**
     * Calculates the least common multiple (LCM) of two numbers.
     *
     * @param int $a The first number.
     * @param int $b The second number.
     * @return int The LCM of the two numbers.
     */
    public static function calculateLCM(int $a, int $b): int
    {
        $gcd = self::calculateGCD($a, $b);
        return ($a * $b) / $gcd;
    }

    /**
     * Calculates the sine of an angle in degrees.
     *
     * @param float $degrees The angle in degrees.
     * @return float The sine of the angle.
     */
    public static function calculateSine(float $degrees): float
    {
        return sin(deg2rad($degrees));
    }

    /**
     * Calculates the cosine of an angle in degrees.
     *
     * @param float $degrees The angle in degrees.
     * @return float The cosine of the angle.
     */
    public static function calculateCosine(float $degrees): float
    {
        return cos(deg2rad($degrees));
    }

    /**
     * Calculates the tangent of an angle in degrees.
     *
     * @param float $degrees The angle in degrees.
     * @return float The tangent of the angle.
     */
    public static function calculateTangent(float $degrees): float
    {
        return tan(deg2rad($degrees));
    }
}
