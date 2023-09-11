<?php

namespace Nxp\Core\Utils\Localization\Language;

class Language
{
    private static $currentLanguage = 'en';
    private static $fallbackLanguage = 'en';

    public static function setCurrentLanguage($language)
    {
        self::$currentLanguage = $language;
    }

    public static function getCurrentLanguage()
    {
        return self::$currentLanguage;
    }

    public static function setFallbackLanguage($fallback)
    {
        self::$fallbackLanguage = $fallback;
    }

    public static function getFallbackLanguage()
    {
        return self::$fallbackLanguage;
    }
}
