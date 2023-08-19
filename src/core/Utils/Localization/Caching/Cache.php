<?php

namespace Nxp\Core\Utils\Localization\Caching;

class Cache
{
    private static $cache = [];

    public static function get($language)
    {
        return self::$cache[$language] ?? null;
    }

    public static function set($language, $translations)
    {
        self::$cache[$language] = $translations;
    }

    public static function clear($language = null)
    {
        if ($language) {
            unset(self::$cache[$language]);
        } else {
            self::$cache = [];
        }
    }
}
