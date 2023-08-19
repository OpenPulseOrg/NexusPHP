<?php

namespace Nxp\Core\Database;

class Transaction
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::connect();
    }

    public function begin()
    {
        $this->pdo->beginTransaction();
    }

    public function commit()
    {
        $this->pdo->commit();
    }

    public function rollBack()
    {
        $this->pdo->rollBack();
    }
}
