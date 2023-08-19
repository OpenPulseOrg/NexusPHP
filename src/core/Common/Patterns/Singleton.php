<?php

namespace Nxp\Core\Common\Patterns;

class Singleton
{
    private static $instances = [];

    protected function __construct() {}

    private function __clone() {}

    public function __wakeup() {}

    public static function getInstance()
    {
        $cls = static::class;
        if (!isset(self::$instances[$cls])) {
            self::$instances[$cls] = new static;
        }
        return self::$instances[$cls];
    }
}
