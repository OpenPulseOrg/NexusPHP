<?php

namespace Nxp\Core\Utils\Navigation\Router;

use Exception;

class RouteCollection
{
    private static $routes = array();
    private static $namedRoutes = array();

    public static function add($method, $pattern, $callback, $name = null, $middleware = null)
    {
        // Replace ':param' with '([^/]+)' in the route pattern
        $pattern = preg_replace('/:[a-z]+/', '([^/]+)', $pattern);

        // Replace '*' with '.*' in the route pattern
        $pattern = str_replace('*', '.*', $pattern);

        $route = array(
            "method" => $method,
            "pattern" => $pattern,
            "callback" => $callback,
            "middleware" => $middleware
        );

        array_push(self::$routes, $route);

        if ($name) {
            self::$namedRoutes[$name] = $route;
        }
    }


    public static function group($prefix, $routes)
    {
        foreach ($routes as $route) {
            list($method, $pattern, $callback) = $route;
            self::add($method, $prefix . $pattern, $callback);
        }
    }

    public static function addOrGroup($method, $pattern, $callback, $groupPrefix = '')
    {
        self::add($method, $pattern, $callback);
        if (!empty($groupPrefix)) {
            self::add($method, $groupPrefix . $pattern, $callback);
        }
    }

    public static function urlFor($name, $params = array())
    {
        if (!isset(self::$namedRoutes[$name])) {
            throw new Exception("No route found for name: $name");
        }

        $pattern = self::$namedRoutes[$name]['pattern'];

        // Replace '([^/]+)' with the given parameters
        foreach ($params as $key => $value) {
            $pattern = preg_replace('/\(\[\^\/\]\+\)/', $value, $pattern, 1);
        }

        return $pattern;
    }

    public static function getRoutes()
    {
        return self::$routes;
    }

}