<?php

namespace Nxp\Core\Common\Abstracts\Bootstrap;

use Nxp\Core\Common\Interfaces\Bootstrap\BootstrapInterface;

/**
 * Provides a default (no-operation) implementation for the BootstrapInterface.
 * This allows developers to override only the methods they are interested in.
 *
 * @package Nxp\Core\Common\Abstracts\Bootstrap
 */
abstract class AbstractBootstrap implements BootstrapInterface
{
    /**
     * Logic to execute before initialization.
     */
    public function preInit() {}

    /**
     * Logic to execute after initialization.
     */
    public function postInit() {}

    /**
     * Logic to execute before loading services.
     */
    public function preLoadServices() {}

    /**
     * Logic to execute after loading services.
     */
    public function postLoadServices() {}

    /**
     * Logic to execute before loading plugins.
     */
    public function preLoadPlugins() {}

    /**
     * Logic to execute after loading plugins.
     */
    public function postLoadPlugins() {}

    /**
     * Logic to execute before loading configurations.
     */
    public function preLoadConfigs() {}

    /**
     * Logic to execute after loading configurations.
     */
    public function postLoadConfigs() {}

    /**
     * Logic to execute before routing.
     */
    public function preRoute() {}

    /**
     * Logic to execute after routing.
     */
    public function postRoute() {}

    /**
     * Logic to execute before starting a session.
     */
    public function preSessionStart() {}

    /**
     * Logic to execute after starting a session.
     */
    public function postSessionStart() {}

    /**
     * Logic to execute before performing system checks.
     */
    public function preSystemChecks() {}

    /**
     * Logic to execute after performing system checks.
     */
    public function postSystemChecks() {}

    /**
     * Logic to execute before cleaning headers.
     */
    public function preCleanHeaders() {}

    /**
     * Logic to execute after cleaning headers.
     */
    public function postCleanHeaders() {}

    /**
     * Logic to execute before setting system preferences.
     */
    public function preSetPreferences() {}

    /**
     * Logic to execute after setting system preferences.
     */
    public function postSetPreferences() {}

    /**
     * Logic to execute before tracking the current page.
     */
    public function preTrackPage() {}

    /**
     * Logic to execute after tracking the current page.
     */
    public function postTrackPage() {}
}
