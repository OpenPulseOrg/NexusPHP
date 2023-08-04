<?php

namespace Nxp\Core\Utils\Conversion;

class DataTransferRateConverter
{
    private static $conversionMatrix = [
        'bps' => [
            'kbps' => 1 / 1000,
            'Mbps' => 1 / 1000000,
            'Gbps' => 1 / 1000000000,
            'Tbps' => 1 / 1000000000000,
            'Pbps' => 1 / 1000000000000000,
        ],
        'kbps' => [
            'bps' => 1000,
            'Mbps' => 1 / 1000,
            'Gbps' => 1 / 1000000,
            'Tbps' => 1 / 1000000000,
            'Pbps' => 1 / 1000000000000,
        ],
        'Mbps' => [
            'bps' => 1000000,
            'kbps' => 1000,
            'Gbps' => 1 / 1000,
            'Tbps' => 1 / 1000000,
            'Pbps' => 1 / 1000000000,
        ],
        'Gbps' => [
            'bps' => 1000000000,
            'kbps' => 1000000,
            'Mbps' => 1000,
            'Tbps' => 1 / 1000,
            'Pbps' => 1 / 1000000,
        ],
        'Tbps' => [
            'bps' => 1000000000000,
            'kbps' => 1000000000,
            'Mbps' => 1000000,
            'Gbps' => 1000,
            'Pbps' => 1 / 1000,
        ],
        'Pbps' => [
            'bps' => 1000000000000000,
            'kbps' => 1000000000000,
            'Mbps' => 1000000000,
            'Gbps' => 1000000,
            'Tbps' => 1000,
        ],
    ];

    /**
     * Convert a data transfer rate value from one unit to another.
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
