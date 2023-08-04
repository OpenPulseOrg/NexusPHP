<?php

namespace Nxp\Core\Common\Interfaces\Plugin;

/**
 * PluginInterface defines the contract for a plugin.
 *
 * @package Nxp\Core\Interfaces
 */
interface PluginInterface
{
  /**
   * Executes the plugin.
   *
   * @return void
   */
  public function execute();

  /**
   * Gets the manifest data for the plugin.
   *
   * @return array The manifest data.
   */
  public function getManifestData(): array;
}
