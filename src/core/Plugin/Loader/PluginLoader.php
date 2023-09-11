<?php

namespace Nxp\Core\Plugin\Loader;

use Nxp\Core\Common\Interfaces\Plugin\PluginInterface;
use Nxp\Core\Plugin\Handler\ErrorHandler;
use Nxp\Core\Plugin\Loader\ControllerLoader;
use Nxp\Core\Plugin\Managers\Manifest;

class PluginLoader
{
    private $plugins = [];
    private $pluginManifests = [];
    private $errorHandler;
    private $manifestManager;
    private $controllerLoader;

    public function __construct(
        Manifest $manifestManager,
        ErrorHandler $errorHandler,
        ControllerLoader $controllerLoader
    ) {
        $this->manifestManager = $manifestManager;
        $this->errorHandler = $errorHandler;
        $this->controllerLoader = $controllerLoader;
    }

    public function loadPluginsFromDirectory(string $directory): array
    {
        if (is_dir($directory)) {
            $plugins = array_filter(glob($directory . '/*'), 'is_dir');

            foreach ($plugins as $plugin) {
                $pluginName = basename($plugin);

                // Load the manifest data for the plugin
                $manifestData = $this->manifestManager->loadManifestData($plugin);
                $this->pluginManifests[$pluginName] = $manifestData;

                // Determine the entry point from the manifest data
                $entryPointPath = $plugin . '/' . $manifestData['entryPoint'];

                if (!is_file($entryPointPath)) {
                    $this->errorHandler->handlePluginError("Plugin entry point not found for plugin $pluginName", $entryPointPath);
                }

                include_once($entryPointPath);

                $className = 'Nxp\\Core\\Plugin\\' . $pluginName . '\\' . $pluginName;

                if (!class_exists($className)) {
                    $this->errorHandler->handlePluginError("Class '$className' not found in plugin file '$entryPointPath'", $entryPointPath);
                }

                $pluginInstance = new $className();

                if ($pluginInstance instanceof PluginInterface) {
                    $this->plugins[] = $pluginInstance;
                    $controllerPath = $plugin . '/Controllers';
                    $this->controllerLoader->loadControllers($controllerPath);
                }
            }
        } else {
            $this->errorHandler->handlePluginError("Plugin directory '$directory' not found", $directory);
        }

        return $this->plugins;
    }

    public function getPluginManifest(string $pluginName): ?array
    {
        return $this->pluginManifests[$pluginName] ?? null;
    }
}
