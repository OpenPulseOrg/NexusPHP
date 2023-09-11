<?php

namespace Nxp\Core\Plugin\Handler;

use Exception;
use Nxp\Core\Utils\Service\Container\Container;
use Nxp\Core\Utils\Error\ErrorFactory;

class ErrorHandler
{
    private $errorHandler;

    public function __construct()
    {
        $container = Container::getInstance();
        $factory = new ErrorFactory($container);
        $this->errorHandler = $factory->createErrorHandler();
    }

    public function handlePluginError($message, $path)
    {
        $this->errorHandler->handleError(
            "Plugin Error",
            null,
            [
                "Message" => $message,
                "Path" => $path,
            ],
            "CRITICAL"
        );

        throw new Exception($message);
    }
}
