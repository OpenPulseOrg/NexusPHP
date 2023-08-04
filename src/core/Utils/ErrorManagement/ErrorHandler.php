<?php

namespace Nxp\Core\Utils\ErrorManagement;

use Nxp\Core\Database\Factories\Query;
use Nxp\Core\Security\Logging\Logger;
use Nxp\Core\Utils\Service\Container;

/**
 * Error handler class for handling PHP errors and exceptions.
 *
 * @package Nxp\Core\Utils\ErrorManagement
 */
class ErrorHandler
{
    /**
     * ErrorHandler constructor.
     *
     * Initializes the error handling by setting the error, exception, and shutdown handlers.
     */
    public function __construct()
    {
        set_error_handler([$this, 'handleError']);
        set_exception_handler([$this, 'handleException']);
        register_shutdown_function([$this, 'handleShutdown']);
    }

    /**
     * Handles PHP errors.
     *
     * @param int    $errno   The error number.
     * @param string $errstr  The error message.
     * @param string $errfile The file where the error occurred.
     * @param int    $errline The line number where the error occurred.
     * @return void
     */
    public function handleError($errno, $errstr, $errfile, $errline)
    {
        $message = "Error: [$errno] $errstr in file $errfile on line $errline . <br>";
        $this->handleErrorOrException($message);
    }

    /**
     * Handles PHP exceptions.
     *
     * @param \Exception $exception The exception object.
     * @return void
     */
    public function handleException($exception)
    {
        $message = "Exception: " . $exception->getMessage() . " in file " . $exception->getFile() . " on line " . $exception->getLine() . "<br>";
        $this->handleErrorOrException($message);
    }

    /**
     * Handles the shutdown of the script.
     *
     * This method is called when the script execution is about to terminate. It checks if there was an error
     * and handles it accordingly.
     *
     * @return void
     */
    public function handleShutdown()
    {
        if (!is_null($error = error_get_last())) {
            $message = "Shutdown Error: [" . $error['type'] . "] " . $error['message'] . " in file " . $error['file'] . " on line " . $error['line'] . "<br>";
            $this->handleErrorOrException($message);
        }
    }

    /**
     * Logs the error message.
     *
     * @param string $message The error message to log.
     * @return void
     */
    private function logError($message)
    {
        $queryFactory = new Query(Container::getInstance());
        $logger = new Logger($queryFactory);

        $logger->log("CRITICAL", "An Error Occurred", [
            "Message" => $message,

        ]);
    }

    /**
     * Displays the error message to the user.
     *
     * This method generates an HTML representation of the error message and additional error details for display.
     *
     * @param array $errorDetails The error details to display.
     * @return void
     */
    private function displayError($errorDetails)
    {
        echo $errorDetails;
        // $errorMessage = $errorDetails['Message'];
        // $errorFile = $errorDetails['File'];
        // $errorLine = $errorDetails['Line'];
        // $errorBacktrace = $errorDetails['Backtrace'];

        // echo "<div class='card' style='margin-bottom: 10px;'>";
        // echo "<div class='card-body'>";
        // echo "<h4 class='card-title'>Error Message</h4>";
        // echo "<p class='card-text'>$errorMessage</p>";
        // echo "<hr>";
        // echo "<h4 class='card-title'>File</h4>";
        // echo "<p class='card-text'>$errorFile</p>";
        // echo "<hr>";
        // echo "<h4 class='card-title'>Line</h4>";
        // echo "<p class='card-text'>$errorLine</p>";
        // echo "<hr>";
        // echo "<h4 class='card-title'>Backtrace</h4>";
        // echo "<button class='btn btn-primary' data-toggle='collapse' data-target='#backtrace' aria-expanded='false' aria-controls='backtrace'>Toggle Backtrace</button>";
        // echo "<pre id='backtrace' class='collapse mt-3' style='font-size: 14px;'>" . print_r($errorBacktrace, true) . "</pre>";
        // echo "<hr>";
        // echo "<h4 class='card-title'>Error Context</h4>";
        // echo "<button class='btn btn-primary' data-toggle='collapse' data-target='#errorDetails' aria-expanded='false' aria-controls='errorDetails'>Toggle Error Details</button>";
        // echo "<div id='errorDetails' class='collapse mt-3'><pre style='font-size: 14px;'>" . print_r($errorDetails, true) . "</pre></div>";
        // echo "<button class='btn btn-primary' data-toggle='collapse' data-target='#serverInfo' aria-expanded='false' aria-controls='serverInfo'>Toggle Server Information</button>";
        // echo "<div id='serverInfo' class='collapse mt-3'><pre style='font-size: 14px;'>" . print_r($_SERVER, true) . "</pre></div>";
        // echo "<button class='btn btn-primary' data-toggle='collapse' data-target='#requestInfo' aria-expanded='false' aria-controls='requestInfo'>Toggle Request Information</button>";
        // echo "<div id='requestInfo' class='collapse mt-3'><pre style='font-size: 14px;'>" . print_r($_REQUEST, true) . "</pre></div>";
        // echo "</div>";
        // echo "</div>";

        // // Include Bootstrap CSS and JavaScript files
        // echo "<link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css'>";
        // echo "<script src='https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js'></script>";
        // echo "<script src='https://code.jquery.com/jquery-3.7.0.min.js'></script>";
    }

    /**
     * Handles either errors or exceptions.
     *
     * This method is responsible for logging the error message and displaying it to the user.
     *
     * @param string $message The error or exception message.
     * @return void
     */
    private function handleErrorOrException($message)
    {
        $this->logError($message);
        $this->displayError($message);
    }
}
