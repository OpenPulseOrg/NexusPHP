<?php

namespace Nxp\Core\Utils\Error;

use Nxp\Core\Config\ConfigHandler;
use Sentry\State\Scope;
use Sentry\ClientBuilder;
use function Sentry\configureScope;

use function Sentry\captureException;
use function Sentry\init;

use Nxp\Core\Security\Logging\Logger;
use Nxp\Core\Database\Factories\Query;

class Management
{
    private $container;
    private $logger;
    private $sentry;
    private $useSentry;

    public function __construct($container)
    {
        $this->container = $container;

        // Initialize the logger
        $queryFactory = new Query($this->container);
        $this->logger = new Logger($queryFactory);

        // Initialize Sentry (assuming you have the Sentry SDK set up)
        $this->sentry = new ClientBuilder();

        $this->useSentry = ConfigHandler::get("app", "USE_SENTRY");

        if ($this->useSentry) {
            $dsn = ConfigHandler::get("app", "SENTRY_DSN");
            if (empty($dsn)) {
                throw new \Exception('Sentry DSN is required but not set in the configuration.');
            }
            init(['dsn' => $dsn]);
        }
    }

    /**
     * Logs the error to your logger and sends it to Sentry.
     *
     * @param string|null $errorType The type or source of the error. Defaults to 'Error'
     * @param \Exception|null $e The exception to handle (optional)
     * @param array $metadata Additional metadata to log
     * @param string $logLevel The log level. Defaults to 'WARNING'
     */
    public function handleError(string $errorType = 'Error', ?\Exception $e = null, array $metadata = [], string $logLevel = 'WARNING')
    {
        $errorDetails = [
            "Message" => "Error Executing $errorType",
        ];

        // If an exception is provided, capture its details
        if ($e) {
            $errorDetails["Error"] = $e->getMessage();
            $errorDetails["Code"] = $e->getCode();
        }

        // Merge in additional metadata
        $errorDetails = array_merge($errorDetails, $metadata);

        // Log the error using your logger
        $this->logger->log($logLevel, $errorType, $errorDetails);

        if ($this->useSentry) {
            // Configure Sentry scope
            configureScope(function (Scope $scope) use ($errorDetails) {
                foreach ($errorDetails as $key => $value) {
                    $scope->setExtra($key, $value);
                }
            });

            // If an exception is provided, send it to Sentry
            if ($e) {
                captureException($e);
            }
        }
    }
}
