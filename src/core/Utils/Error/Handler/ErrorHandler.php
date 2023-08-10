<?php

namespace Nxp\Core\Utils\Error\Handler;

use Nxp\Core\Utils\Error\Services\LoggerService;
use Nxp\Core\Utils\Error\Services\SentryService;

class ErrorHandler
{
    private $loggerService;
    private $sentryService;

    public function __construct(LoggerService $loggerService, ?SentryService $sentryService = null)
    {
        $this->loggerService = $loggerService;
        $this->sentryService = $sentryService;
    }

    public function handleError(string $errorType = 'Error', ?\Exception $e = null, array $metadata = [], string $logLevel = 'WARNING')
    {
        // Log to local logger
        $this->loggerService->log($logLevel, $errorType, array_merge([
            "Message" => "Error Executing $errorType",
            "Error" => $e ? $e->getMessage() : '',
            "Code" => $e ? $e->getCode() : ''
        ], $metadata));

        if ($this->sentryService) {
            try {
                $this->sentryService->captureError([
                    "Message" => "Error Executing $errorType",
                    "Error" => $e ? $e->getMessage() : '',
                    "Code" => $e ? $e->getCode() : ''
                ], $e);
            } catch (\Exception $sentryException) {
                // Handle or log any exceptions arising from Sentry. This ensures your application doesn't crash due to Sentry issues.
                $this->loggerService->log("ERROR", "SentryLoggingError", ["Error" => $sentryException->getMessage()]);
            }
        }
    }


    // Add more methods as needed...
}
