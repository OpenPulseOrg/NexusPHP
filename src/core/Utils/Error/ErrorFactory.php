<?php

namespace Nxp\Core\Utils\Error;

use Nxp\Core\Config\ConfigurationManager;
use Nxp\Core\Utils\Error\Handler\ErrorHandler;
use Nxp\Core\Utils\Error\Services\LoggerService;
use Nxp\Core\Utils\Error\Services\SentryService;

class ErrorFactory
{
    private $container;
    private $useSentry;

    public function __construct($container)
    {
        $this->container = $container;
        $this->useSentry = ConfigurationManager::get("app", "sentry.use");
    }

    public function createLoggerService(): LoggerService
    {
        return new LoggerService($this->container);
    }

        public function createSentryService(): ?SentryService
        {
            if ($this->useSentry) {
                return new SentryService();
            }
            return null;
        }

    public function createErrorHandler(): ErrorHandler
    {
        $loggerService = $this->createLoggerService();
        $sentryService = $this->createSentryService();

        return new ErrorHandler($loggerService, $sentryService);
    }
}
