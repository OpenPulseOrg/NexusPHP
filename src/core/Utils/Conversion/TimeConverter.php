<?php

namespace Nxp\Core\Utils\Conversion;

class TimeConverter
{
    private static $conversionMatrix = [
        'seconds' => [
            'minutes' => 1 / 60,
            'hours' => 1 / 3600,
            'days' => 1 / 86400,
            'weeks' => 1 / 604800,
            'months' => 1 / 2592000,
            'years' => 1 / 31536000,
        ],
        'minutes' => [
            'seconds' => 60,
            'hours' => 1 / 60,
            'days' => 1 / 1440,
            'weeks' => 1 / 10080,
            'months' => 1 / 43829.1,
            'years' => 1 / 525948,
        ],
        'hours' => [
            'seconds' => 3600,
            'minutes' => 60,
            'days' => 1 / 24,
            'weeks' => 1 / 168,
            'months' => 1 / 730.484,
            'years' => 1 / 8765.81,
        ],
        'days' => [
            'seconds' => 86400,
            'minutes' => 1440,
            'hours' => 24,
            'weeks' => 1 / 7,
            'months' => 1 / 30.4369,
            'years' => 1 / 365.242,
        ],
        'weeks' => [
            'seconds' => 604800,
            'minutes' => 10080,
            'hours' => 168,
            'days' => 7,
            'months' => 1 / 4.34524,
            'years' => 1 / 52.1775,
        ],
        'months' => [
            'seconds' => 2592000,
            'minutes' => 43829.1,
            'hours' => 730.484,
            'days' => 30.4369,
            'weeks' => 4.34524,
            'years' => 1 / 12,
        ],
        'years' => [
            'seconds' => 31536000,
            'minutes' => 525948,
            'hours' => 8765.81,
            'days' => 365.242,
            'weeks' => 52.1775,
            'months' => 12,
        ],
    ];

    /**
     * Convert a time value from one unit to another.
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
