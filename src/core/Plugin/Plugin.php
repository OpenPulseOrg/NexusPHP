<?php

namespace Nxp\Core\Plugin;

use Nxp\Core\Plugin\Loader\PluginLoader;
use Nxp\Core\Utils\Service\Locator\Locator;
class Plugin
{
    private $pluginLoaderManager;

    public function __construct(PluginLoader $pluginLoaderManager)
    {
        $this->pluginLoaderManager = $pluginLoaderManager;
    }

    public function loadPlugins(): array
    {
        $locator = Locator::getInstance();
        $directory = $locator->getPath("plugin", "root");
        return $this->pluginLoaderManager->loadPluginsFromDirectory($directory);
    }


    public function getPluginManifest(string $pluginName): ?array
    {
        return $this->pluginLoaderManager->getPluginManifest($pluginName);
    }
}
