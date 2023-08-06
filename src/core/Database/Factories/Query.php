<?php

namespace Nxp\Core\Database\Factories;

use Nxp\Core\Database\Internal\InternalQuery;
use Nxp\Core\Utils\Service\Container;

/**
 * The Query class provides a fluent interface for building and executing database queries.
 *
 * @package Nxp\Core\Database\Factories
 */
class Query
{
    /**
     * The dependency injection container instance.
     *
     * @var Container
     */
    private $container;

    /**
     * Query constructor.
     *
     * @param Container $container The dependency injection container instance.
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Creates a new InternalQuery instance.
     *
     * @return InternalQuery A new instance of the InternalQuery class.
     */
    public function createQuery(): InternalQuery
    {
        return new InternalQuery($this->container);
    }

    /**
     * Start building a SELECT query.
     *
     * @param string $table The name of the table to query.
     * @param string|array $columns The columns to select (default is '*').
     * @return InternalQuery A new InternalQuery instance with the SELECT query setup.
     */
    public function select($table, $columns = '*'): InternalQuery
    {
        return $this->createQuery()->table($table)->select($columns);
    }

    /**
     * Execute an INSERT query.
     *
     * @param string $table The name of the table to insert data into.
     * @param array $data An associative array representing the data to be inserted.
     * @return bool True on success, false on failure.
     */
    public function insert($table, $data): bool
    {
        return $this->createQuery()->table($table)->insert($data);
    }

    /**
     * Execute an UPDATE query.
     *
     * @param string $table The name of the table to update.
     * @param array $data An associative array representing the data to be updated.
     * @return bool True on success, false on failure.
     */
    public function update($table, $data): bool
    {
        return $this->createQuery()->table($table)->update($data);
    }

    /**
     * Execute a DELETE query.
     *
     * @param string $table The name of the table from which to delete rows.
     * @return bool True on success, false on failure.
     */
    public function delete($table): bool
    {
        return $this->createQuery()->table($table)->delete();
    }

    /**
     * Add a WHERE clause to the query.
     *
     * @param string $table The name of the table to query.
     * @param string|array $where The WHERE clause or conditions to apply.
     * @param array $params Optional parameters to bind to the query.
     * @return InternalQuery A new InternalQuery instance with the WHERE clause added.
     */
    public function where($table, $where, $params = []): InternalQuery
    {
        return $this->createQuery()->table($table)->where($where, $params);
    }

    /**
     * Add an ORDER BY clause to the query.
     *
     * @param string $table The name of the table to query.
     * @param string $column The column to use for sorting.
     * @param string $direction The direction of sorting (default is 'ASC').
     * @return InternalQuery A new InternalQuery instance with the ORDER BY clause added.
     */
    public function orderBy($table, $column, $direction = 'ASC'): InternalQuery
    {
        return $this->createQuery()->table($table)->orderBy($column, $direction);
    }

    /**
     * Set a LIMIT for the query results.
     *
     * @param string $table The name of the table to query.
     * @param int $limit The maximum number of rows to return.
     * @return InternalQuery A new InternalQuery instance with the LIMIT set.
     */
    public function limit($table, $limit): InternalQuery
    {
        return $this->createQuery()->table($table)->limit($limit);
    }

    /**
     * Add a JOIN clause to the query.
     *
     * @param string $table The name of the table to query.
     * @param string $joinTable The name of the table to join.
     * @param string $condition The join condition.
     * @return InternalQuery A new InternalQuery instance with the JOIN clause added.
     */
    public function join($table, $joinTable, $condition): InternalQuery
    {
        return $this->createQuery()->table($table)->join($joinTable, $condition);
    }
}
