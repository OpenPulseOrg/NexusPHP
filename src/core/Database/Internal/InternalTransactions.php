<?php

namespace Nxp\Core\Database\Internal;

use Nxp\Core\Database\Factories\Query;
use Nxp\Core\Security\Logging\Logger;
use Nxp\Core\Utils\Navigation\Redirects;
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

    /**
     * Initializes the database connection using the Database class.
     *
     * @return void
     */
    public function __construct(Container $container = null)
    {
        $this->container = $container;
        $this->pdo = $container->get('pdo');

        $queryFactory = new Query($container);
        $this->logger = new Logger($queryFactory);
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
             $this->logger->log("CRITICAL", "SQL Error Occured", [
                "Error" => $e->getMessage(),
                "Code" => $e->getCode(),
                "SQL Command" => "BEGIN TRANSACTION"
            ]);
            Redirects::redirectToPreviousPageOrHome();
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
             $this->logger->log("CRITICAL", "SQL Error Occured", [
                "Error" => $e->getMessage(),
                "Code" => $e->getCode(),
                "SQL Command" => "COMMIT TRANSACTION"
            ]);
            Redirects::redirectToPreviousPageOrHome();
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
             $this->logger->log("CRITICAL", "SQL Error Occured", [
                "Error" => $e->getMessage(),
                "Code" => $e->getCode(),
                "SQL Command" => "ROLLBACK TRANSACTION"
            ]);
            Redirects::redirectToPreviousPageOrHome();
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
             $this->logger->log("CRITICAL", "SQL Error Occured", [
                "Error" => $e->getMessage(),
                "Code" => $e->getCode(),
                "SQL Command" => "CHECK IF TRANSACTION IS ACTIVE"
            ]);
            Redirects::redirectToPreviousPageOrHome();
        }
    }
}
