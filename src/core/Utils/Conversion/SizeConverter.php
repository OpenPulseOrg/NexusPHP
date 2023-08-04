<?php

namespace Nxp\Core\Utils\Conversion;

class SizeConverter
{
    private static $conversionMatrix = [
        'bytes' => [
            'kilobytes' => 1 / 1024,
            'megabytes' => 1 / 1048576,
            'gigabytes' => 1 / 1073741824,
            'terabytes' => 1 / 1099511627776,
            'petabytes' => 1 / 1125899906842624,
            'exabytes'  => 1 / 1152921504606846976,
        ],
        'kilobytes' => [
            'bytes'     => 1024,
            'megabytes' => 1 / 1024,
            'gigabytes' => 1 / 1048576,
            'terabytes' => 1 / 1073741824,
            'petabytes' => 1 / 1099511627776,
            'exabytes'  => 1 / 1125899906842624,
        ],
        'megabytes' => [
            'bytes'     => 1048576,
            'kilobytes' => 1024,
            'gigabytes' => 1 / 1024,
            'terabytes' => 1 / 1048576,
            'petabytes' => 1 / 1073741824,
            'exabytes'  => 1 / 1099511627776,
        ],
        'gigabytes' => [
            'bytes'     => 1073741824,
            'kilobytes' => 1048576,
            'megabytes' => 1024,
            'terabytes' => 1 / 1024,
            'petabytes' => 1 / 1048576,
            'exabytes'  => 1 / 1073741824,
        ],
        'terabytes' => [
            'bytes'     => 1099511627776,
            'kilobytes' => 1073741824,
            'megabytes' => 1048576,
            'gigabytes' => 1024,
            'petabytes' => 1 / 1024,
            'exabytes'  => 1 / 1048576,
        ],
        'petabytes' => [
            'bytes'     => 1125899906842624,
            'kilobytes' => 1099511627776,
            'megabytes' => 1073741824,
            'gigabytes' => 1048576,
            'terabytes' => 1024,
            'exabytes'  => 1 / 1024,
        ],
        'exabytes'  => [
            'bytes'     => 1152921504606846976,
            'kilobytes' => 1125899906842624,
            'megabytes' => 1099511627776,
            'gigabytes' => 1073741824,
            'terabytes' => 1048576,
            'petabytes' => 1024,
        ],
    ];

    /**
     * Convert a size value from one unit to another.
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
