<?php

namespace Nxp\Core\Utils\Tracking;

use Nxp\Core\Utils\Session\Session;

/**
 * PageTracker class for tracking current and previous page URLs.
 *
 * @package Nxp\Core\Utils\Tracking
 */
class PageTracker
{
    private static $current_page = '';
    private static $previous_page = '';

    /**
     * Tracks the current and previous page by storing them in the session.
     *
     * @return void
     */
    public static function track()
    {
        if (Session::get('current_page')) {
            self::$previous_page = Session::get('current_page');
        }
        self::$current_page = $_SERVER['REQUEST_URI'];

        Session::set('current_page', self::$current_page);
        Session::set('previous_page', self::$previous_page);
    }

    /**
     * Gets the current page that was tracked.
     *
     * @return string The current page URL.
     */
    public static function getCurrentPage()
    {
        self::$current_page = Session::get('current_page');
        return self::$current_page;
    }

    /**
     * Gets the previous page that was tracked.
     *
     * @return string The previous page URL.
     */
    public static function getPreviousPage()
    {
        self::$previous_page = Session::get('previous_page');
        return self::$previous_page;
    }

    /**
     * Clears the current and previous page values from the session.
     *
     * @return void
     */
    public static function clear()
    {
        self::$current_page = '';
        self::$previous_page = '';
        Session::delete('current_page');
        Session::delete('previous_page');
    }

    /**
     * Returns the current page name from the URL.
     *
     * @return string
     */
    public static function getPageName()
    {
        $directoryURI = $_SERVER['REQUEST_URI'];
        $path = parse_url($directoryURI, PHP_URL_PATH);
        $components = explode('/', $path);

        return $components[1];
    }
}
