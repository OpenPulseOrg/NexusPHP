<?php

namespace Nxp\Core\Config;

use Exception;
use Nxp\Core\Utils\Error\ErrorFactory;
use Nxp\Core\Utils\Service\Container\Container;
use Nxp\Core\Utils\Service\Locator\Locator;
/**
 * The ConfigurationManager class provides methods for loading and retrieving configuration files.
 *
 * @package Nxp\Core\Config
 */
class ConfigurationManager
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
        $locator = Locator::getInstance();

        // Construct the path to the configuration file.
        $path = $locator->getPath("core", "config") . "/{$filename}.php";

        // Check if the configuration file exists.
        if (file_exists($path)) {
            // Load the configuration file and store it in memory.
            $config = require $path;
            self::$configs[$filename] = $config;
            return $config;
        } else {

            $factory = new ErrorFactory(Container::getInstance());

            $errorHandler = $factory->createErrorHandler();

            $errorHandler->handleError("Config Handler Error", null, ["Message" => "Config file not found", "Filename" => $filename], "CRITICAL");

            throw new Exception("$filename was not found in the config handler!");
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
            // Split the key into category and subkey based on the delimiter (assuming ".").
            $parts = explode(".", $key);
            $category = $parts[0] ?? null;
            $subkey = $parts[1] ?? null;

            if ($category && $subkey) {
                // Check if category and subkey both exist in the configuration.
                return $config[$category][$subkey] ?? $default;
            } else {
                // If only category is provided, return the whole category or default value.
                return $config[$category] ?? $default;
            }
        }
    }
}
