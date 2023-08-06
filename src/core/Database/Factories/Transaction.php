<?php

namespace Nxp\Core\Database\Factories;

use Nxp\Core\Database\Internal\InternalTransactions;
use Nxp\Core\Utils\Service\Container;

/**
 * The Transaction class provides a simple interface to manage database transactions.
 *
 * This class wraps the functionality of the InternalTransactions class to facilitate
 * transaction management in the database.
 */
class Transaction
{
    /**
     * The InternalTransactions instance used for transaction management.
     *
     * @var InternalTransactions
     */
    private $transactions;

    /**
     * The dependency injection container.
     *
     * @var Container
     */
    private $container;

    /**
     * Transaction constructor.
     *
     * @param Container $container The dependency injection container to be used for transaction management.
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->transactions = new InternalTransactions($this->container);
    }

    /**
     * Begin a new database transaction.
     *
     * @return void
     */
    public function beginTransaction(): void
    {
        $this->transactions->beginTransaction();
    }

    /**
     * Commit the current database transaction.
     *
     * @return void
     */
    public function commitTransaction(): void
    {
        $this->transactions->commitTransaction();
    }

    /**
     * Rollback the current database transaction.
     *
     * @return void
     */
    public function rollbackTransaction(): void
    {
        $this->transactions->rollbackTransaction();
    }

    /**
     * Check if a transaction is currently active.
     *
     * @return bool True if a transaction is active, false otherwise.
     */
    public function inTransaction(): bool
    {
        return $this->transactions->inTransaction();
    }
}
