<?php

namespace Nxp\Core\Utils\Navigation\Router;

use Exception;

class RouteLoader
{
    public static function loadFromFile($file)
    {
        if (file_exists($file)) {
            require $file;
        } else {
            throw new Exception("Router file does not exist.");
        }
    }
    public static function load($file)
    {
        self::loadFromFile($file);
    }
}
