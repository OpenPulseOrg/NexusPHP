<?php

namespace Nxp\Core\Utils\Localization\Managers;

use Nxp\Core\Middleware\MiddlewareChain;

class Middleware
{
    private static $middlewareChain = null;

    public static function setMiddlewareChain(MiddlewareChain $middlewareChain)
    {
        self::$middlewareChain = $middlewareChain;
    }

    public static function process($input)
    {
        if (self::$middlewareChain) {
            return self::$middlewareChain->handle($input);
        }
        return $input;
    }
}
