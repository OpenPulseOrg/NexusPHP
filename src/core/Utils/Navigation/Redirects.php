<?php

namespace Nxp\Core\Utils\Navigation;

use Nxp\Core\Utils\Tracking\PageTracker;

/**
 * Redirects class for managing page redirects.
 *
 * @package Nxp\Core\Utils\Navigation
 */
class Redirects
{

    /**
     * Redirects the user to the previous page if available, or to the home page if not.
     *
     * @return void
     */
    public static function redirectToPreviousPageOrHome()
    {
        $previousPage = PageTracker::getPreviousPage();
        $redirectUrl = !empty($previousPage) ? $previousPage : "/";
        header("Location: " . $redirectUrl);
        exit();
    }


    /**
     * Redirects the user to a specified location.
     *
     * @param string $location The URL to redirect to.
     *
     * @return void
     */
    public static function redirectToLocation($location)
    {
        header("Location: " . $location);
        exit;
    }

    /**
     * Redirects the user to the root page.
     *
     * @return void
     */
    public static function redirectToRoot()
    {
        header("Location: /");
        exit();
    }


    /**
     * Redirects the user to the 404 error page.
     *
     * @return void
     */
    public static function redirectTo404()
    {
        header("Location: /404");
        exit();
    }

    /**
     * Redirects the user to a specified URL with a delay.
     *
     * @param string $url    The URL to redirect to.
     * @param int    $delay  The delay time in seconds before redirecting.
     *
     * @return void
     */
    public static function redirectToUrlWithDelay($url, $delay)
    {
        header("Refresh: " . $delay . "; URL=" . $url);
        exit();
    }

    /**
     * Redirects the user to a specified location if a condition is met, or to a default location if not.
     *
     * @param bool   $condition       The condition to check.
     * @param string $trueLocation    The URL to redirect to if the condition is true.
     * @param string $defaultLocation The URL to redirect to if the condition is false.
     *
     * @return void
     */
    public static function redirectToLocationIf($condition, $trueLocation, $defaultLocation)
    {
        $redirectUrl = $condition ? $trueLocation : $defaultLocation;
        self::redirectToLocation($redirectUrl);
    }

    /**
     * Redirects the user to a specified URL with additional query parameters.
     *
     * @param string $url       The base URL to redirect to.
     * @param array  $params    Additional query parameters to append to the URL.
     *
     * @return void
     */
    public static function redirectToUrlWithParams($url, $params)
    {
        $queryString = http_build_query($params);
        $redirectUrl = $url . '?' . $queryString;
        self::redirectToLocation($redirectUrl);
    }

    /**
     * Redirects the user to a specified URL using a secure HTTPS connection.
     *
     * @param string $url The URL to redirect to.
     *
     * @return void
     */
    public static function redirectToSecureUrl($url)
    {
        $secureUrl = str_replace('http://', 'https://', $url);
        self::redirectToLocation($secureUrl);
    }

    /**
     * Redirects the user to a specified URL with a custom HTTP status code.
     *
     * @param string $url    The URL to redirect to.
     * @param int    $statusCode  The HTTP status code to use for the redirect.
     *
     * @return void
     */
    public static function redirectToUrlWithStatusCode($url, $statusCode)
    {
        header("Location: " . $url, true, $statusCode);
        exit();
    }
}
