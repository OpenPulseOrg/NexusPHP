<?php

namespace Nxp\Core\Utils\Localization;

use Nxp\Core\Middleware\MiddlewareChain;
use Nxp\Core\Utils\Localization\Managers\Language;
use Nxp\Core\Utils\Localization\Managers\Middleware;
use Nxp\Core\Utils\Localization\Managers\Translation;

class Translator
{
    public static function initialize($language = 'en', $fallback = 'en', MiddlewareChain $middlewareChain = null)
    {
        Language::setCurrentLanguage($language);
        Language::setFallbackLanguage($fallback);
        Translation::loadTranslations();
        if ($middlewareChain !== null) {
            Middleware::setMiddlewareChain($middlewareChain);
        }
    }

    public static function get($key, $replacements = [], $default = null)
    {
        return Translation::getTranslation($key, $replacements, $default);
    }

    public static function setLanguage($language)
    {
        Language::setCurrentLanguage($language);
        Translation::loadTranslations();
    }

    public static function getLanguage()
    {
        return Language::getCurrentLanguage();
    }

    public static function setFallbackLanguage($fallback)
    {
        Language::setFallbackLanguage($fallback);
    }

    public static function getFallbackLanguage()
    {
        return Language::getFallbackLanguage();
    }

    public static function reloadTranslations()
    {
        Translation::loadTranslations();
    }

    public static function getAllTranslations()
    {
        return Translation::getAllTranslations();
    }

    public static function has($key)
    {
        return Translation::hasTranslation($key);
    }
}
