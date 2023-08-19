<?php

namespace Nxp\Core\Common\Interfaces\Plugin;

/**
 * PluginInterface defines the contract for a plugin.
 *
 * This interface provides the basic methods that a plugin must implement.
 * Plugins are components that extend the functionality of an application without
 * directly modifying its core code. They can be used to add new features or alter
 * existing behavior in a modular and flexible way.
 *
 * @package Nxp\Core\Interfaces
 */
interface PluginInterface
{
    /**
     * Executes the plugin.
     *
     * This method will be called when the plugin is triggered or activated. It
     * should contain the logic and actions that the plugin needs to perform.
     * The plugin can interact with the application, modify data, or perform any
     * other tasks to achieve its intended purpose.
     *
     * @return void
     */
    public function execute();

    /**
     * Gets the manifest data for the plugin.
     *
     * The manifest data typically contains information about the plugin, such as
     * its name, version, author, description, and any configuration settings it
     * requires. The data is usually represented as an associative array with
     * specific keys for each piece of information.
     *
     * @return array The manifest data. An associative array containing information
     *              about the plugin.
     *              Example: [
     *                  'name' => 'My Awesome Plugin',
     *                  'version' => '1.0.0',
     *                  'author' => 'John Doe',
     *                  'description' => 'A plugin that does something awesome.',
     *                  'settings' => [
     *                      'setting1' => 'value1',
     *                      'setting2' => 'value2',
     *                      // ...
     *                  ],
     *              ]
     */
    public function getManifestData(): array;
}
