<?php

namespace Nxp\Core\Database\Internal;

use Nxp\Core\Utils\Error\ErrorFactory;
use Nxp\Core\Utils\Service\Container;
use PDOException;

/**
 * Transactions class for managing database transactions.
 *
 * @package Nxp\Core\Database
 */
final class InternalTransactions
{
    private $pdo;
    private $container;
    private $logger;
    private $errorHandler;

    /**
     * Initializes the database connection using the Database class.
     *
     * @return void
     */
    public function __construct(Container $container = null)
    {
        $this->container = $container;
        $this->pdo = $container->get('pdo');

        $factory = new ErrorFactory($container);
        $this->errorHandler = $factory->createErrorHandler();
    }

    /**
     * Initiates a transaction.
     *
     * @return void
     */
    public function beginTransaction()
    {
        try {
            $this->pdo->beginTransaction();
        } catch (PDOException $e) {
            $this->errorHandler->handleError(
                "SQL Error Occurred",
                null,
                [
                    "Error" => $e->getMessage(),
                    "Code" => $e->getCode(),
                    "SQL Command" => "BEGIN TRANSACTION"
                ],
                "CRITICAL"
            );
        }
    }

    /**
     * Commits a transaction.
     *
     * @return void
     */
    public function commitTransaction()
    {
        try {
            $this->pdo->commit();
        } catch (PDOException $e) {
            $this->errorHandler->handleError(
                "SQL Error Occurred",
                null,
                [
                    "Error" => $e->getMessage(),
                    "Code" => $e->getCode(),
                    "SQL Command" => "COMMIT TRANSACTION"
                ],
                "CRITICAL"
            );
        }
    }

    /**
     * Rolls back a transaction.
     *
     * @return void
     */
    public function rollbackTransaction()
    {
        try {
            $this->pdo->rollBack();
        } catch (PDOException $e) {
            
            $this->errorHandler->handleError(
                "SQL Error Occurred",
                null,
                [
                    "Error" => $e->getMessage(),
                    "Code" => $e->getCode(),
                    "SQL Command" => "ROLLBACK TRANSACTION"
                ],
                "CRITICAL"
            );
        }
    }

    /**
     * Checks if a transaction is active.
     *
     * @return bool
     */
    public function inTransaction()
    {
        try {
            return $this->pdo->inTransaction();
        } catch (PDOException $e) {
            $this->errorHandler->handleError(
                "SQL Error Occurred",
                null,
                [
                    "Error" => $e->getMessage(),
                    "Code" => $e->getCode(),
                    "SQL Command" => "CHECK IF TRANSACTION IS ACTIVE"
                ],
                "CRITICAL"
            );
        }
    }
}
