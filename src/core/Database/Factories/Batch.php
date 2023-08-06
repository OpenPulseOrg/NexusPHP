<?php

namespace Nxp\Core\Database\Factories;

use Nxp\Core\Database\Internal\InternalBatch;
use Nxp\Core\Utils\Service\Container;

/**
 * Class Batch
 *
 * A class for handling database batch operations.
 *
 * @package Nxp\Core\Database\Factories
 */
class Batch
{
    /**
     * The internal batch instance used to perform database operations.
     *
     * @var InternalBatch
     */
    private $batch;

    /**
     * The dependency injection container instance.
     *
     * @var Container
     */
    private $container;

    /**
     * Batch constructor.
     *
     * Creates a new Batch object with the specified container.
     *
     * @param Container $container The dependency injection container to use.
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Create a new InternalBatch instance.
     *
     * @return InternalBatch Returns a new InternalBatch instance.
     */
    public function createBatch(): InternalBatch
    {
        return new InternalBatch($this->container);
    }

    /**
     * Update records in batches for a specified table.
     *
     * @param string $table The name of the table to update records in.
     * @param array $data An associative array containing column-value pairs to be updated.
     * @param string $column The column to be used in the WHERE clause for updating.
     *
     * @return bool|int Returns the number of affected rows or FALSE on failure.
     */
    public function updateBatch($table, $data, $column)
    {
        return $this->batch->updateBatch($table, $data, $column);
    }

    /**
     * Insert records in batches into a specified table.
     *
     * @param string $table The name of the table to insert records into.
     * @param array $data An array of associative arrays representing the data to be inserted.
     *
     * @return bool|int Returns the number of affected rows or FALSE on failure.
     */
    public function insertBatch($table, $data)
    {
        return $this->batch->insertBatch($table, $data);
    }

    /**
     * Delete records in batches from a specified table.
     *
     * @param string $table The name of the table to delete records from.
     * @param string $column The column to be used in the WHERE clause for deleting.
     * @param array $values An array of values for the specified column to match against.
     *
     * @return bool|int Returns the number of affected rows or FALSE on failure.
     */
    public function deleteBatch($table, $column, $values)
    {
        return $this->batch->deleteBatch($table, $column, $values);
    }
}
