<?php

namespace Nxp\Core\Utils\Conversion;

class LengthConverter
{
    private static $conversionMatrix = [
        'millimeters' => [
            'centimeters' => 0.1,
            'meters' => 0.001,
            'kilometers' => 0.000001,
            'inches' => 0.0393701,
            'feet' => 0.00328084,
            'yards' => 0.00109361,
            'miles' => 0.000000621371,
        ],
        'centimeters' => [
            'millimeters' => 10,
            'meters' => 0.01,
            'kilometers' => 0.00001,
            'inches' => 0.393701,
            'feet' => 0.0328084,
            'yards' => 0.0109361,
            'miles' => 0.00000621371,
        ],
        'meters' => [
            'millimeters' => 1000,
            'centimeters' => 100,
            'kilometers' => 0.001,
            'inches' => 39.3701,
            'feet' => 3.28084,
            'yards' => 1.09361,
            'miles' => 0.000621371,
        ],
        'kilometers' => [
            'millimeters' => 1000000,
            'centimeters' => 100000,
            'meters' => 1000,
            'inches' => 39370.1,
            'feet' => 3280.84,
            'yards' => 1093.61,
            'miles' => 0.621371,
        ],
        'inches' => [
            'millimeters' => 25.4,
            'centimeters' => 2.54,
            'meters' => 0.0254,
            'kilometers' => 0.0000254,
            'feet' => 0.0833333,
            'yards' => 0.0277778,
            'miles' => 0.0000157828,
        ],
        'feet' => [
            'millimeters' => 304.8,
            'centimeters' => 30.48,
            'meters' => 0.3048,
            'kilometers' => 0.0003048,
            'inches' => 12,
            'yards' => 0.333333,
            'miles' => 0.000189394,
        ],
        'yards' => [
            'millimeters' => 914.4,
            'centimeters' => 91.44,
            'meters' => 0.9144,
            'kilometers' => 0.0009144,
            'inches' => 36,
            'feet' => 3,
            'miles' => 0.000568182,
        ],
        'miles' => [
            'millimeters' => 1609344,
            'centimeters' => 160934.4,
            'meters' => 1609.34,
            'kilometers' => 1.60934,
            'inches' => 63360,
            'feet' => 5280,
            'yards' => 1760,
        ],
    ];

    /**
     * Convert a length value from one unit to another.
     *
     * @param float  $value    The value to convert.
     * @param string $fromUnit The unit to convert from.
     * @param string $toUnit   The unit to convert to.
     *
     * @return float The converted value.
     */
    public static function convert($value, $fromUnit, $toUnit)
    {
        if (!isset(self::$conversionMatrix[$fromUnit]) || !isset(self::$conversionMatrix[$fromUnit][$toUnit])) {
            throw new \InvalidArgumentException("Unsupported conversion: $fromUnit to $toUnit");
        }

        return $value * self::$conversionMatrix[$fromUnit][$toUnit];
    }
}
