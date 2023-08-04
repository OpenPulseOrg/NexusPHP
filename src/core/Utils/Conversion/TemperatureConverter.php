<?php

namespace Nxp\Core\Utils\Conversion;

class TemperatureConverter
{
    /**
     * Convert a temperature value from one unit to another.
     *
     * @param float  $value    The value to convert.
     * @param string $fromUnit The unit to convert from.
     * @param string $toUnit   The unit to convert to.
     *
     * @return float The converted value.
     */
    public static function convert($value, $fromUnit, $toUnit)
    {
        switch ($fromUnit) {
            case 'celsius':
                return self::convertFromCelsius($value, $toUnit);
            case 'fahrenheit':
                return self::convertFromFahrenheit($value, $toUnit);
            case 'kelvin':
                return self::convertFromKelvin($value, $toUnit);
            default:
                throw new \InvalidArgumentException("Unsupported conversion: $fromUnit to $toUnit");
        }
    }

    private static function convertFromCelsius($value, $toUnit)
    {
        switch ($toUnit) {
            case 'celsius':
                return $value;
            case 'fahrenheit':
                return ($value * 9 / 5) + 32;
            case 'kelvin':
                return $value + 273.15;
            default:
                throw new \InvalidArgumentException("Unsupported conversion: celsius to $toUnit");
        }
    }

    private static function convertFromFahrenheit($value, $toUnit)
    {
        switch ($toUnit) {
            case 'celsius':
                return ($value - 32) * 5 / 9;
            case 'fahrenheit':
                return $value;
            case 'kelvin':
                return ($value + 459.67) * 5 / 9;
            default:
                throw new \InvalidArgumentException("Unsupported conversion: fahrenheit to $toUnit");
        }
    }

    private static function convertFromKelvin($value, $toUnit)
    {
        switch ($toUnit) {
            case 'celsius':
                return $value - 273.15;
            case 'fahrenheit':
                return ($value * 9 / 5) - 459.67;
            case 'kelvin':
                return $value;
            default:
                throw new \InvalidArgumentException("Unsupported conversion: kelvin to $toUnit");
        }
    }
}
