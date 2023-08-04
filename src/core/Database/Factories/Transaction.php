<?php

namespace Nxp\Core\Database\Factories;

use Nxp\Core\Database\Internal\InternalTransactions;
use Nxp\Core\Utils\Service\Container;

class Transaction
{
    private $transactions;
    private $container;

    /**
     * Transaction constructor.
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->transactions = new InternalTransactions($this->container);
    }

    public function beginTransaction(): void
    {
        $this->transactions->beginTransaction();
    }

    public function commitTransaction(): void
    {
        $this->transactions->commitTransaction();
    }

    public function rollbackTransaction(): void
    {
        $this->transactions->rollbackTransaction();
    }

    public function inTransaction(): bool
    {
        return $this->transactions->inTransaction();
    }
}
