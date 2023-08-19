<?php

namespace Nxp\Core\Bootstrap;

use Exception;
use Nxp\Core\Plugin\Plugin;
use Nxp\Core\Utils\Session\Manager;
use Nxp\Core\Utils\Service\Container\Container;
use Nxp\Core\Plugin\Managers\Manifest;
use Nxp\Core\Utils\Error\ErrorFactory;
use Nxp\Core\Plugin\Loader\PluginLoader;
use Nxp\Core\Config\ConfigurationManager;
use Nxp\Core\Plugin\Handler\ErrorHandler;
use Nxp\Core\Utils\Localization\Translator;
use Nxp\Core\Utils\Service\Container\Locator\Locator;
use Nxp\Core\Plugin\Loader\ControllerLoader;
use Nxp\Core\Utils\Navigation\Router\Loader;
use Nxp\Core\Utils\Navigation\Router\Dispatcher;

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
     * @var BootstrapInterface|null
     */
    private $userBootstrap;

    /**
     * Bootstrap constructor.
     *
     * @param Container $container The dependency injection container.
     */
    public function __construct(Container $container)
    {
        $this->container = $container;

        // Initialize the user bootstrap if it exists
        if (class_exists('\Nxp\App\config\bootstrap')) {
            $this->userBootstrap = new \Nxp\App\config\bootstrap();
        }
    }

    /**
     * Loads all required methods and classes that are required to run on every load.
     *
     * @return void
     */
    public static function init()
    {
        $container = Container::getInstance();

        $bootstrap = new self($container);

        // Execute the user's pre-init logic, if provided
        if ($bootstrap->userBootstrap) {
            $bootstrap->userBootstrap->preInit();
        }

        if ($bootstrap->userBootstrap) {
            $bootstrap->userBootstrap->preInit();
        }

        $bootstrap->loadServices();
        if ($bootstrap->userBootstrap) {
            $bootstrap->userBootstrap->postLoadServices();
        }

        $bootstrap->checkSystemTables();
        if ($bootstrap->userBootstrap) {
            $bootstrap->userBootstrap->postSystemChecks();
        }

        $bootstrap->cleanServerHeaders();
        if ($bootstrap->userBootstrap) {
            $bootstrap->userBootstrap->postCleanHeaders();
        }

        $bootstrap->setSystemPreferences();
        if ($bootstrap->userBootstrap) {
            $bootstrap->userBootstrap->postSetPreferences();
        }

        $bootstrap->loadConfigs();
        if ($bootstrap->userBootstrap) {
            $bootstrap->userBootstrap->postLoadConfigs();
        }

        $bootstrap->loadPlugins();
        if ($bootstrap->userBootstrap) {
            $bootstrap->userBootstrap->postLoadPlugins();
        }

        $bootstrap->startSession();
        if ($bootstrap->userBootstrap) {
            $bootstrap->userBootstrap->postSessionStart();
        }

        $bootstrap->trackPage();
        if ($bootstrap->userBootstrap) {
            $bootstrap->userBootstrap->postTrackPage();
        }

        $bootstrap->loadRoutes();
        if ($bootstrap->userBootstrap) {
            $bootstrap->userBootstrap->postRoute();
        }

        // Execute the user's post-init logic, if provided
        if ($bootstrap->userBootstrap) {
            $bootstrap->userBootstrap->postInit();
        }
    }

    /**
     * Loads the services from the configuration files.
     *
     * @return void
     */
    private function loadServices()
    {
        $locator = Locator::getInstance();

        $databaseServices = require $locator->getPath("services", "database");
        $coreServices = require $locator->getPath("services", "core");

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
        $locator = Locator::getInstance();

        // Load routes from web.php
        Loader::loadFromFile($locator->getPath("core", "routes") . "/web.php");

        // Load routes from api.php
        Loader::loadFromFile($locator->getPath("core", "routes") . "/api.php");

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
        ConfigurationManager::load("app");
        ConfigurationManager::load("constants");
    }

    /**
     * Loads plugins and executes them.
     *
     * @return void
     * @throws Exception if an error occurs while executing a plugin.
     */
    private function loadPlugins()
    {
        $locator = Locator::getInstance();
        $pluginLoaderManager = new PluginLoader(new Manifest, new ErrorHandler, new ControllerLoader);
        $pluginManager = new Plugin($pluginLoaderManager);

        try {
            $plugins = $pluginManager->loadPlugins();

            foreach ($plugins as $plugin) {
                $plugin->execute();
            }
        } catch (\Exception $e) {
            $factory = new ErrorFactory(Container::getInstance());

            $errorHandler = $factory->createErrorHandler();

            $errorHandler->handleError("Plugin Error", null, ["Message" => "Error Executing Plugin"], "WARNING");

            throw new Exception($e->getMessage(), $e->getCode(), $e);
        }
    }


    /**
     * Sets the system preferences such as the default timezone.
     *
     * @return void
     */
    public function setSystemPreferences()
    {
        // Set timezone
        date_default_timezone_set(ConfigurationManager::get("app", "system.time_zone"));

        $langauge = ConfigurationManager::get("app", "system.default_language");

        // Initialize translator
        if (empty($langauge)) {
            Translator::initialize("en", "en");
        } else {
            Translator::initialize(ConfigurationManager::get("app", "system.default_language"), "en");
        }

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
