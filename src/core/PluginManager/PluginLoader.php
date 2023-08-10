<?php

namespace Nxp\Core\PluginManager;

use Exception;
use ReflectionClass;
use Nxp\Core\Common\Interfaces\Plugin\PluginInterface;
use Nxp\Core\Utils\Error\ErrorFactory;
use Nxp\Core\Utils\Service\Container;

/**
 * PluginLoader is responsible for loading and managing plugins in the application.
 *
 * @package Nxp\Core\PluginManager
 */
class PluginLoader
{
    private $plugins;
    private $errorHandler;

    public function __construct()
    {
        $this->plugins = [];

        $container = Container::getInstance();

        $factory = new ErrorFactory($container);
        $this->errorHandler = $factory->createErrorHandler();
    }

    /**
     * Loads all plugins located in the "plugins" directory.
     *
     * For each plugin, it includes the "init.php" file and instantiates the plugin class.
     * If the plugin class implements the PluginInterface, it is added to the list of loaded plugins.
     *
     * @return void
     */
    public function loadPlugins()
    {
        $directory = PLUGIN_ROOT_PATH;

        if (is_dir($directory)) {
            $plugins = array_filter(glob($directory . '/*'), 'is_dir');

            foreach ($plugins as $plugin) {
                $pluginName = basename($plugin);
                $path = $plugin . '/' . $pluginName . '.php';

                if (!is_file($path)) {
                    $this->errorHandler->handleError(
                        "Plugin Error",
                        null,
                        [
                            "Message" => "Plugin class file not found for plugin $plugin",
                            "Path" => $path,
                        ],
                        "CRITICAL"
                    );
                    throw new Exception("Plugin class file not found for plugin $plugin");
                }

                include_once($path);

                $className = 'Nxp\\Core\\Plugin\\' . $pluginName . '\\' . $pluginName;

                if (!class_exists($className)) {
                    $this->errorHandler->handleError(
                        "Plugin Error",
                        null,
                        [
                            "Message" => "Class '$className' not found in plugin file '$path'",
                            "Path" => $path,
                            "Classname" => $className
                        ],
                        "CRITICAL"
                    );

                    throw new Exception("Class '$className' not found in plugin file '$path'");
                }

                $pluginInstance = new $className();

                // Load manifest.json file
                $manifestPath = $plugin . '/manifest.json';
                if (is_file($manifestPath)) {
                    $manifestData = json_decode(file_get_contents($manifestPath), true);
                    if (json_last_error() == JSON_ERROR_NONE) {
                        $reflectionClass = new ReflectionClass($pluginInstance);
                        $reflectionProperty = $reflectionClass->getProperty('manifestData');
                        $reflectionProperty->setAccessible(true);
                        $reflectionProperty->setValue($pluginInstance, $manifestData);
                    } else {
                        $this->errorHandler->handleError(
                            "Plugin Error",
                            null,
                            [
                                "Message" => "Invalid manifest.json file for plugin $plugin",
                                "Path" => $manifestPath,
                            ],
                            "CRITICAL"
                        );

                        throw new Exception("Invalid manifest.json file for plugin $plugin");
                    }
                }

                if ($pluginInstance instanceof PluginInterface) {
                    $this->plugins[] = $pluginInstance;

                    $controllerPath = $plugin . '/Controllers';
                    $this->loadControllers($controllerPath);
                }
            }
        } else {
            $this->errorHandler->handleError(
                "Plugin Error",
                null,
                [
                    "Message" => "Plugin directory '$directory' not found",
                    "Path" => $directory,
                ],
                "CRITICAL"
            );

            throw new Exception("Plugin Directory '$directory' not found");
        }
    }

    /**
     * Loads controllers from the specified directory path.
     *
     * @param string $controllerPath The path to the controller directory.
     * @return void
     */
    private function loadControllers($controllerPath)
    {
        // Check if the controller directory exists.
        if (is_dir($controllerPath)) {
            // Get all PHP files in the controller directory.
            $controllerFiles = glob($controllerPath . '/*.php');

            // Loop through each controller file.
            foreach ($controllerFiles as $controllerFile) {
                // Include the controller file.
                include_once($controllerFile);
            }
        } else {
            $this->errorHandler->handleError(
                "Plugin Error",
                null,
                [
                    "Message" => "Controller directory '$controllerPath' not found",
                    "Path" => $controllerPath,
                ],
                "CRITICAL"
            );
        }
    }

    /**
     * Returns the list of loaded plugins.
     *
     * @return array The list of loaded plugins.
     */
    public function getPlugins()
    {
        return $this->plugins;
    }

    /**
     * Executes all loaded plugins by calling their `execute` method.
     *
     * @return void
     */
    public function executePlugins()
    {
        foreach ($this->plugins as $plugin) {
            $plugin->execute();
        }
    }
}
