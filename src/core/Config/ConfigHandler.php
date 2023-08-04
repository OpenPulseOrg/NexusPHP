<?php

namespace Nxp\Core\Config;

use Exception;
use Nxp\Core\Database\Factories\Query;
use Nxp\Core\Security\Logging\Logger;
use Nxp\Core\Utils\Service\Container;

/**
 * The ConfigHandler class provides methods for loading and retrieving configuration files.
 *
 * @package Nxp\Core\Config
 */
class ConfigHandler
{
    // Static property to hold the loaded configurations.
    private static $configs = [];

    /**
     * This function loads a configuration file by its filename and returns the configuration as an array.
     * The loaded configuration is also stored in memory for quick access.
     * 
     * @param string $filename The name of the configuration file to load.
     * 
     * @throws Exception If the configuration file cannot be found.
     * 
     * @return array The loaded configuration as an associative array.
     */
    public static function load($filename)
    {
        // Construct the path to the configuration file.
        $path = __DIR__ . '/../../../app/config/' . $filename . '.php';

        // Check if the configuration file exists.
        if (file_exists($path)) {
            // Load the configuration file and store it in memory.
            $config = require $path;
            self::$configs[$filename] = $config;
            return $config;
        } else {
            $queryFactory = new Query(Container::getInstance());
            $logger = new Logger($queryFactory);

            $logger->log("CRITICAL", "Config Handler Error", [
                "Message" => "Error Executing Plugin",
                "Message" => "Config file not found",
                "Filename" => $filename,
                "Error" => $e->getMessage(),
                "Code" => $e->getCode(),
            ]);

            exit();
        }
    }

    /**
     * This function retrieves a value from a loaded configuration file by its filename and key.
     * If the key is not specified or null, the entire configuration file is returned.
     * If the key is not found in the configuration file, the default value is returned.
     * 
     * @param string $filename The name of the configuration file to retrieve from.
     * @param string|null $key The key to retrieve from the configuration file, or null to retrieve the entire file.
     * @param mixed $default The default value to return if the key is not found in the configuration file.
     * 
     * @return mixed The retrieved value from the configuration file.
     */
    public static function get($filename, $key = null, $default = null)
    {
        // If the configuration file has not been loaded, load it now.
        if (!isset(self::$configs[$filename])) {
            self::load($filename);
        }

        // Retrieve the configuration file from memory.
        $config = self::$configs[$filename];

        // If the key is null or not specified, return the entire configuration file.
        if ($key === null) {
            return $config;
        } else {
            // If the key is found in the configuration file, return its value.
            // Otherwise, return the default value.
            return array_key_exists($key, $config) ? $config[$key] : $default;
        }
    }
}
