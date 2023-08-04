<?php

namespace Nxp\Core\Security\Logging;

use Nxp\Core\Database\Factories\Query as FactoriesQuery;
use Nxp\Core\Database\Query;
use Nxp\Core\Utils\Manipulator\DateManipulator;
use Nxp\Core\Utils\Randomization\RandomGenerator;

/**
 * Logger class provides methods for logging messages to a database table or local .log files.
 *
 * @package Nxp\Core\Security\Logging
 */
class Logger
{
    private $queryFactory;
    private $table = 'logs';
    private $levels = [
        'DEBUG',
        'INFO',
        'WARNING',
        'ERROR',
        'CRITICAL'
    ];
    private $logDestination;

    const LOG_DESTINATION_DATABASE = 'database';
    const LOG_DESTINATION_FILES = 'files';

    private $currentLogFile;
    private $logRotationDate;

    /**
     * Creates a new instance of the Logger class.
     * Sets up the database query builder to use the specified table.
     *
     * @param string $logDestination The log destination, either 'database' or 'files'.
     *
     * @return void
     */
    public function __construct(FactoriesQuery $queryFactory, $logDestination = self::LOG_DESTINATION_FILES)
    {
      
        $this->queryFactory = $queryFactory;
        $this->logDestination = $logDestination;

        if ($this->logDestination === self::LOG_DESTINATION_FILES) {
            $this->currentLogFile = $this->getLogFile();
            $this->logRotationDate = DateManipulator::getCurrentDate();
        }
    }

    /**
     * Logs a message with the specified level and optional metadata.
     *
     * @param string $level The log level, one of 'INFO', 'WARNING', 'ERROR', or 'CRITICAL'.
     * @param string $message The message to log.
     * @param mixed $metadata Optional metadata to include with the log message.
     *
     * @return void
     *
     * @throws \InvalidArgumentException If an invalid log level is provided or if the metadata is not an array.
     */
    public function log($level, $message, $metadata = null)
    {
        if (!in_array($level, $this->levels)) {
            throw new \InvalidArgumentException('Invalid log level.');
        }
        if (isset($metadata) && !is_array($metadata)) {
            throw new \InvalidArgumentException('Metadata must be an array.');
        }

        if ($this->logDestination === self::LOG_DESTINATION_DATABASE) {
            $this->logToDatabase($level, $message, $metadata);
        } elseif ($this->logDestination === self::LOG_DESTINATION_FILES) {
            $this->checkAndRotateLogFile();
            $this->logToFiles($level, $message, $metadata);
        }
    }

    /**
     * Logs a message to the database.
     *
     * @param string $level The log level, one of 'INFO', 'WARNING', 'ERROR', or 'CRITICAL'.
     * @param string $message The message to log.
     * @param mixed $metadata Optional metadata to include with the log message.
     *
     * @return void
     */
    private function logToDatabase($level, $message, $metadata)
    {
        $data = [
            'level' => $level,
            'message' => $message,
            'timestamp' => date('Y-m-d H:i:s'),
            'metadata' => isset($metadata) ? (is_array($metadata) ? json_encode($metadata) : $metadata) : null,
            'uuid' => RandomGenerator::generateUUID()
        ];

        $this->queryFactory->insert($this->table, $data);
    }

    /**
     * Logs a message to local .log files.
     *
     * @param string $level The log level, one of 'DEBUG', 'INFO', 'WARNING', 'ERROR', or 'CRITICAL'.
     * @param string $message The message to log.
     * @param mixed $metadata Optional metadata to include with the log message.
     *
     * @return void
     */
    private function logToFiles($level, $message, $metadata)
    {
        $logMessage = DateManipulator::getCurrentDateTime() . " [$level] $message";

        if (isset($metadata)) {
            $logMessage .= ' Metadata: ' . json_encode($metadata);
        }

        $logMessage .= PHP_EOL;
        file_put_contents($this->currentLogFile, $logMessage, FILE_APPEND);
    }

    /**
     * Get the current log file name based on the date.
     *
     * @return string The log file name.
     */
    private function getLogFile()
    {
        $logDir = __DIR__ . "/../../../../logs/";

        if(!is_dir($logDir)){
            mkdir($logDir);
        }
        
        return $logDir . DateManipulator::getCurrentDate() . ".log";
    }

    /**
     * Check if a new date has started and rotate the log file accordingly.
     *
     * @return void
     */
    private function checkAndRotateLogFile()
    {
        $currentDate = DateManipulator::getCurrentDate();

        // Check if a new date has started and rotate the log file accordingly
        if ($currentDate !== $this->logRotationDate) {
            $this->currentLogFile = $this->getLogFile();
            $this->logRotationDate = $currentDate;
        }
    }

}
