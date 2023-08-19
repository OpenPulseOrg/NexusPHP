<?php

namespace Nxp\Core\Utils\Localization\Managers;

use Nxp\Core\Utils\Localization\Caching\Cache;
use Nxp\Core\Utils\Localization\Loaders\Loader;

class Translation
{
    private static $translations = [];

    public static function loadTranslations()
    {
        $language = Language::getCurrentLanguage();
        $translations = Cache::get($language);

        if (!$translations) {
            $loader = new Loader();
            $translations = $loader->loadFromFile($language);
            Cache::set($language, $translations);
        }

        self::$translations = $translations;
    }

    public static function getTranslation($key, $replacements = [], $default = null)
    {
        $translation = self::$translations[$key] ?? null;

        if (!$translation && Language::getFallbackLanguage() !== Language::getCurrentLanguage()) {
            Language::setCurrentLanguage(Language::getFallbackLanguage());
            self::loadTranslations();
            $translation = self::$translations[$key] ?? $default;
            Language::setCurrentLanguage(Language::getCurrentLanguage());
        }

        foreach ($replacements as $key => $value) {
            $translation = str_replace(":{$key}", $value, $translation);
        }

        return Middleware::process($translation);
    }

    public static function reloadTranslations()
    {
        self::loadTranslations();
    }

    public static function getAllTranslations()
    {
        return self::$translations;
    }

    public static function hasTranslation($key)
    {
        return isset(self::$translations[$key]);
    }
}
