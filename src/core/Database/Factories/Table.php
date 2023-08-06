<?php

namespace Nxp\Core\Database\Factories;

use Nxp\Core\Database\Internal\InternalTable;
use Nxp\Core\Utils\Service\Container;

/**
 * The Table class is a factory for creating and managing database tables.
 *
 * This class provides methods to create, modify, and delete database tables using an InternalTable object.
 */
class Table
{
    /**
     * The dependency injection container.
     *
     * @var Container
     */
    private $container;

    /**
     * The InternalTable instance used for table operations.
     *
     * @var InternalTable
     */
    private $table;

    /**
     * Constructor for the Table class.
     *
     * @param Container $container The dependency injection container to be used for table operations.
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->table = new InternalTable($container); 
    }

    /**
     * Create a new InternalTable instance.
     *
     * This method returns a new instance of the InternalTable class with the current dependency injection container.
     *
     * @return InternalTable A new instance of the InternalTable class.
     */
    public function createTableObject(): InternalTable
    {
        return new InternalTable($this->container);
    }

    /**
     * Create a new database table with the specified columns, engine, and options.
     *
     * @param string $table The name of the table to be created.
     * @param array $columns An array of column definitions for the new table.
     * @param string $engine The storage engine to be used for the table (optional).
     * @param string $options Additional options for the table creation (optional).
     * @return void
     */
    public function createTable($table, $columns, $engine = '', $options = '')
    {
        $this->createTableObject()->createTable($table, $columns, $engine, $options);
    }

    /**
     * Check if a table exists in the database.
     *
     * @param string $table The name of the table to check for existence.
     * @return bool True if the table exists, false otherwise.
     */
    public function tableExists($table)
    {
        return $this->createTableObject()->tableExists($table);
    }

    /**
     * Drop an existing database table.
     *
     * @param string $table The name of the table to be dropped.
     * @return void
     */
    public function dropTable($table)
    {
        $this->createTableObject()->dropTable($table);
    }

    /**
     * Add a new column to an existing database table.
     *
     * @param string $table The name of the table to add the column to.
     * @param string $column The name of the column to be added.
     * @param string $type The data type of the new column.
     * @return void
     */
    public function addColumn($table, $column, $type)
    {
        $this->createTableObject()->addColumn($table, $column, $type);
    }

    /**
     * Drop a column from an existing database table.
     *
     * @param string $table The name of the table to drop the column from.
     * @param string $column The name of the column to be dropped.
     * @return void
     */
    public function dropColumn($table, $column)
    {
        $this->createTableObject()->dropColumn($table, $column);
    }

    /**
     * Add an index to an existing database table.
     *
     * @param string $table The name of the table to add the index to.
     * @param string $column The name of the column to create the index on.
     * @param bool $unique Flag indicating whether the index is unique (optional, default is false).
     * @return void
     */
    public function addIndex($table, $column, $unique = false)
    {
        $this->createTableObject()->addIndex($table, $column, $unique);
    }

    /**
     * Drop an index from an existing database table.
     *
     * @param string $table The name of the table to drop the index from.
     * @param string $column The name of the column on which the index is created.
     * @return void
     */
    public function dropIndex($table, $column)
    {
        $this->createTableObject()->dropIndex($table, $column);
    }

    /**
     * Add a foreign key constraint to an existing database table.
     *
     * @param string $table The name of the table to add the foreign key to.
     * @param string $column The name of the column to create the foreign key on.
     * @param string $refTable The name of the referenced table.
     * @param string $refColumn The name of the referenced column.
     * @param string $onDelete The action to be taken on DELETE (optional, default is 'CASCADE').
     * @param string $onUpdate The action to be taken on UPDATE (optional, default is 'CASCADE').
     * @return void
     */
    public function addForeignKey($table, $column, $refTable, $refColumn, $onDelete = 'CASCADE', $onUpdate = 'CASCADE')
    {
        $this->createTableObject()->addForeignKey($table, $column, $refTable, $refColumn, $onDelete, $onUpdate);
    }

    /**
     * Drop a foreign key constraint from an existing database table.
     *
     * @param string $table The name of the table to drop the foreign key from.
     * @param string $column The name of the column on which the foreign key is created.
     * @return void
     */
    public function dropForeignKey($table, $column)
    {
        $this->createTableObject()->dropForeignKey($table, $column);
    }

    /**
     * Disable foreign key checks on the database.
     *
     * @return void
     */
    public function disableForeignKeyChecks()
    {
        $this->createTableObject()->disableForeignKeyChecks();
    }

    /**
     * Enable foreign key checks on the database.
     *
     * @return void
     */
    public function enableForeignKeyChecks()
    {
        $this->createTableObject()->enableForeignKeyChecks();
    }
}
