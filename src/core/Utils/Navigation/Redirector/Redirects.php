<?php

namespace Nxp\Core\Utils\Navigation\Redirector;

class Redirect
{
    /**
     * Redirects the user to a specified location.
     *
     * @param string $location The URL to redirect to.
     * @param int|null $statusCode Optional HTTP status code for the redirect.
     * @return void
     */
    public static function to($location, $statusCode = null)
    {
        if ($statusCode) {
            header("Location: " . $location, true, $statusCode);
        } else {
            header("Location: " . $location);
        }
        exit;
    }

    /**
     * Redirects the user to a specified URL with a delay.
     *
     * @param string $url The URL to redirect to.
     * @param int $delay The delay time in seconds before redirecting.
     * @return void
     */
    public static function toWithDelay($url, $delay)
    {
        header("Refresh: " . $delay . "; URL=" . $url);
        exit();
    }

    /**
     * Redirects back to the referring page. If not available, falls back to a default location.
     *
     * @param string $default The default URL to redirect to if the referrer is not available.
     * @return void
     */
    public static function back($default = '/')
    {
        $referrer = $_SERVER['HTTP_REFERER'] ?? $default;
        self::to($referrer);
    }

    /**
     * Redirects the user to a specified URL with additional query parameters.
     *
     * @param string $url Base URL to redirect to.
     * @param array $params Additional query parameters to append to the URL.
     * @return void
     */
    public static function toWithParams($url, $params)
    {
        $queryString = http_build_query($params);
        self::to($url . '?' . $queryString);
    }

    /**
     * Redirects to a secure (HTTPS) or non-secure (HTTP) version of a given URL.
     *
     * @param string $url The URL to redirect to.
     * @param bool $secure If true, redirects to HTTPS, otherwise to HTTP.
     * @return void
     */
    public static function toSecure($url, $secure = true)
    {
        $protocol = $secure ? 'https://' : 'http://';
        $secureUrl = preg_replace("(^https?://)", $protocol, $url);
        self::to($secureUrl);
    }
}
