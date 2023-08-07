<?php

namespace Nxp\Core\Bootstrap;

use Exception;
use Nxp\Core\Hook\Hook;
use Nxp\Core\Config\ConfigHandler;
use Nxp\Core\Utils\Session\Manager;
use Nxp\Core\Security\Logging\Logger;
use Nxp\Core\Utils\Service\Container;
use Nxp\Core\Database\Factories\Query;
use Nxp\Core\PluginManager\PluginLoader;
use Nxp\Core\Utils\Error\Management;
use Nxp\Core\Utils\Error\Sentry\Client;
use Nxp\Core\Utils\Error\Sentry\Event;
use Nxp\Core\Utils\Navigation\Router\Loader;
use Nxp\Core\Utils\Navigation\Router\Dispatcher;

use function Sentry\init;

/**
 * Bootstrap class for initializing the system, loading configurations and plugins,
 * setting system preferences, starting a new session, and tracking the current page.
 *
 * @package Nxp\Core\Bootstrap
 */
class Bootstrap
{
    /**
     * @var Container The dependency injection container.
     */
    private $container;

    /**
     * Bootstrap constructor.
     *
     * @param Container $container The dependency injection container.
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Loads all required methods and classes that are required to run on every load.
     *
     * @return void
     */
    public static function init()
    {
        
        $container = Container::getInstance();
        
        new Management($container);
        
        $bootstrap = new self($container);
        $bootstrap->loadServices();
        $bootstrap->checkSystemTables();
        $bootstrap->cleanServerHeaders();
        $bootstrap->setSystemPreferences();
        $bootstrap->loadConfigs();
        $bootstrap->loadPlugins();
        $bootstrap->startSession();
        $bootstrap->trackPage();
        $bootstrap->loadRoutes();
        $bootstrap->executeHooks();
    }

    /**
     * Executes the hooks after the routes are loaded.
     *
     * @return void
     */
    private function executeHooks()
    {
        Hook::executeHook("after_route");
    }

    /**
     * Loads the services from the configuration files.
     *
     * @return void
     */
    private function loadServices()
    {
        $databaseServices = require __DIR__ . "/../Utils/Service/Containers/database.service.php";
        $coreServices = require __DIR__ . "/../Utils/Service/Containers/core.service.php";

        $this->container->loadConfig($databaseServices);
        $this->container->loadConfig($coreServices);
    }

    /**
     * Checks the system tables in the database.
     *
     * @return void
     */
    private function checkSystemTables()
    {
        $systemChecks = $this->container->get('systemChecks');
        $systemChecks->checkTables();
    }

    /**
     * Cleans the headers sent by the server.
     *
     * @return void
     */
    private function cleanServerHeaders()
    {
        $serverInfo = $this->container->get("serverInfo");
        $serverInfo->cleanHeaders();
    }

    /**
     * Loads the routes for the framework.
     *
     * @return void
     */
    private function loadRoutes()
    {
        // Load routes from web.php
        Loader::loadFromFile(__DIR__ . "/../../../app/routes/web.php");

        // Load routes from api.php
        Loader::loadFromFile(__DIR__ . "/../../../app/routes/api.php");

        // Dispatch the request to the appropriate route
        Dispatcher::dispatch();
    }


    /**
     * Loads the application and constants configuration files.
     *
     * @return void
     */
    private function loadConfigs()
    {

        ConfigHandler::load("app");
        ConfigHandler::load("constants");
    }

    /**
     * Loads plugins and executes them.
     *
     * @return void
     * @throws Exception if an error occurs while executing a plugin.
     */
    private function loadPlugins()
    {
        $pluginLoader = new PluginLoader();

        try {
            $plugins = $pluginLoader->loadPlugins();
            $plugins = $pluginLoader->getPlugins();

            foreach ($plugins as $plugin) {
                $plugin->execute();
            }
        } catch (\Exception $e) {
            // Handle the exception as needed
            $queryFactory = new Query($this->container);
            $logger = new Logger($queryFactory);

            $logger->log("WARNING", "Plugin Error", [
                "Message" => "Error Executing Plugin",
                "Error" => $e->getMessage(),
                "Code" => $e->getCode()
            ]);

            throw new Exception($e);
        }
    }

    /**
     * Sets the system preferences such as the default timezone.
     *
     * @return void
     */
    public function setSystemPreferences()
    {
        date_default_timezone_set(ConfigHandler::get("app", "TIME_ZONE"));
        // (new ErrorHandler());
    }

    /**
     * Starts a new session.
     *
     * @return void
     */
    private function startSession()
    {
        Manager::getInstance();
    }

    /**
     * Tracks the current page.
     *
     * @return void
     */
    private function trackPage()
    {
        $container = Container::getInstance();
        // Get the PageTracker service from the container
        $pageTracker = $container->get('pageTracker');

        // Use the PageTracker service
        $pageTracker->track();
    }
}
