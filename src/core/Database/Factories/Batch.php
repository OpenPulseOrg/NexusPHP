<?php

namespace Nxp\Core\Database\Factories;

use Nxp\Core\Database\Internal\InternalBatch;
use Nxp\Core\Utils\Service\Container;

class Batch
{
    private $batch;
    private $container;

    /**
     * Batch constructor.
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @return InternalBatch
     */
    public function createBatch(): InternalBatch
    {
        return new InternalBatch($this->container);
    }

    public function updateBatch($table, $data, $column)
    {
        return $this->batch->updateBatch($table, $data, $column);
    }

    public function insertBatch($table, $data)
    {
        return $this->batch->insertBatch($table, $data);
    }

    public function deleteBatch($table, $column, $values)
    {
        return $this->batch->deleteBatch($table, $column, $values);
    }
}
