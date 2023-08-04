<?php

namespace Nxp\Core\Database\Factories;

use Nxp\Core\Database\Internal\InternalQuery;
use Nxp\Core\Utils\Service\Container;

class Query
{
    private $container;

    /**
     * @return Query
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function createQuery(): InternalQuery
    {
        return new InternalQuery($this->container);
    }

    public function select($table, $columns = '*'): InternalQuery
    {
        return $this->createQuery()->table($table)->select($columns);
    }

    public function insert($table, $data): bool
    {
        return $this->createQuery()->table($table)->insert($data);
    }

    public function update($table, $data): bool
    {
        return $this->createQuery()->table($table)->update($data);
    }

    public function delete($table): bool
    {
        return $this->createQuery()->table($table)->delete();
    }

    public function where($table, $where, $params = []): InternalQuery
    {
        return $this->createQuery()->table($table)->where($where, $params);
    }

    public function orderBy($table, $column, $direction = 'ASC'): InternalQuery
    {
        return $this->createQuery()->table($table)->orderBy($column, $direction);
    }

    public function limit($table, $limit): InternalQuery
    {
        return $this->createQuery()->table($table)->limit($limit);
    }

    public function join($table, $joinTable, $condition): InternalQuery
    {
        return $this->createQuery()->table($table)->join($joinTable, $condition);
    }
}
