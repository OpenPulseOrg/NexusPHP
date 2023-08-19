<?php

namespace Nxp\Core\Utils\Service\Locator;

use Nxp\Core\Common\Patterns\Singleton;

class Locator extends Singleton
{
    private static $instance = null;
    private $config;
    private $rootDir;

    protected function __construct()
    {
        $this->rootDir = dirname(__DIR__, 5);
        $this->loadConfig();
    }

    private function loadConfig()
    {
        $this->config = json_decode(file_get_contents(__DIR__ . '/Config/config.json'), true);
    }

    public function getPath($key, $subKey = null, $params = [])
    {
        $path = $subKey ? $this->config['paths'][$key][$subKey] : $this->config['paths'][$key];

        // Replace placeholders with parameters
        foreach ($params as $paramKey => $paramValue) {
            $path = str_replace("{{$paramKey}}", $paramValue, $path);
        }

        return $this->rootDir . $path;
    }
}
