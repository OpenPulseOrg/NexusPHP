<?php

namespace Nxp\Core\Database\Factories;

use Nxp\Core\Database\Internal\InternalTable;
use Nxp\Core\Utils\Service\Container;

class Table
{
    private $container;
    private $table;

    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->table = new InternalTable($container); 
    }


    public function createTableObject(): InternalTable
    {
        return new InternalTable($this->container);
    }

    public function createTable($table, $columns, $engine = '', $options = '')
    {
        $this->createTableObject()->createTable($table, $columns, $engine, $options);
    }

    public function tableExists($table)
    {
        return $this->createTableObject()->tableExists($table);
    }

    public function dropTable($table)
    {
        $this->createTableObject()->dropTable($table);
    }

    public function addColumn($table, $column, $type)
    {
        $this->createTableObject()->addColumn($table, $column, $type);
    }

    public function dropColumn($table, $column)
    {
        $this->createTableObject()->dropColumn($table, $column);
    }

    public function addIndex($table, $column, $unique = false)
    {
        $this->createTableObject()->addIndex($table, $column, $unique);
    }

    public function dropIndex($table, $column)
    {
        $this->createTableObject()->dropIndex($table, $column);
    }

    public function addForeignKey($table, $column, $refTable, $refColumn, $onDelete = 'CASCADE', $onUpdate = 'CASCADE')
    {
        $this->createTableObject()->addForeignKey($table, $column, $refTable, $refColumn, $onDelete, $onUpdate);
    }

    public function dropForeignKey($table, $column)
    {
        $this->createTableObject()->dropForeignKey($table, $column);
    }

    public function disableForeignKeyChecks()
    {
        $this->createTableObject()->disableForeignKeyChecks();
    }

    public function enableForeignKeyChecks()
    {
        $this->createTableObject()->enableForeignKeyChecks();
    }
}
