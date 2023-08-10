<?php

namespace Nxp\Core\Database\Internal;

use Nxp\Core\Utils\Error\ErrorFactory;
use Nxp\Core\Utils\Service\Container;
use PDO;
use PDOException;

/**
 * Table class for managing database tables, including creating, dropping, and modifying tables.
 *
 * @package Nxp\Core\Database
 */
final class InternalTable
{
    private $container;
    private $errorHandler;
    private $pdo;
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

        $factory = new ErrorFactory($container);
        $this->errorHandler = $factory->createErrorHandler();
    }

    /**
     * Creates a new table in the database with the given name, columns, primary key, engine, and options.
     *
     * @param string $table The name of the new table to be created.
     * @param array $columns An associative array of column names and their data types.
     * @param string $primaryKey The name of the primary key column, if any.
     * @param string $engine The name of the database engine to use, defaulting to InnoDB.
     * @param string $options Any additional options to include in the SQL statement, if any.
     * @return void
     */
    public function createTable($table, $columns, $engine = '', $options = '')
    {
        try {
            // Add id column as the first column
            $columns = array_merge(['id' => 'SERIAL'], $columns);

            // Add created_on column as the last column
            $columns['created_on'] = 'TIMESTAMP';

            $columns_sql = [];
            foreach ($columns as $name => $type) {
                $columns_sql[] = "$name $type";
            }
            $columns_sql = implode(', ', $columns_sql);

            // Check if table exists
            if ($this->tableExists($table)) {
                return;
            }

            $sql = "CREATE TABLE $table ($columns_sql";

            // Add engine for MySQL
            if ($this->pdo->getAttribute(PDO::ATTR_DRIVER_NAME) === 'mysql' && !empty($engine)) {
                $sql .= ") ENGINE=$engine";
            } else {
                $sql .= ")";
            }

            // Add options if provided
            if (!empty($options)) {
                $sql .= " $options";
            }

            $this->pdo->exec($sql);
        } catch (PDOException $e) {
            throw new PDOException($e);
        }
    }


    /**
     * Checks if an index with the given name exists on the specified table.
     *
     * @param string $table The name of the table to check.
     * @param string $index_name The name of the index to check for.
     * @return bool Whether the index exists on the table.
     */
    private function indexExists($table, $index_name)
    {
        $stmt = $this->pdo->prepare("SHOW INDEXES FROM $table WHERE Key_name = ?");
        $stmt->execute([$index_name]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return !empty($result);
    }

    /**
     * Checks if the specified table exists in the database.
     *
     * @param string $table The name of the table to check.
     * @return bool Whether the table exists in the database.
     */
    public function tableExists($table)
    {
        try {
            $stmt = $this->pdo->query("SELECT 1 FROM $table LIMIT 1");
            $result = $stmt !== false;
        } catch (PDOException $e) {
            return false;
        }
        return $result;
    }

    /**
     * Checks if the specified tables exist in the database.
     *
     * @param array $tables An array of table names to check.
     * @return array An associative array of table names and their existence in the database.
     */
    public function tablesExist($tables)
    {
        $results = array();

        foreach ($tables as $table) {
            try {
                $stmt = $this->pdo->query("SELECT 1 FROM $table LIMIT 1");
                $result = $stmt !== false;
            } catch (PDOException $e) {
                $result = false;
            }
            $results[$table] = $result;
        }

        return $results;
    }



    /**
     * Drops the specified table from the database.
     *
     * @param string $table The name of the table to drop.
     * @return void
     */
    public function dropTable($table)
    {
        try {
            $sql = "DROP TABLE IF EXISTS $table";
            $this->pdo->exec($sql);
        } catch (PDOException $e) {
            $this->errorHandler->handleError(
                "SQL Error Occurred",
                null,
                [
                    "Code" => $e->getCode(),
                    "Error" => $e->getMessage(),
                    "Table" => $table,
                    "SQL Command" => $sql
                ],
                "ERROR"
            );
        }
    }

    /**
     * Adds a new column to the specified table.
     *
     * @param string $table The name of the table to add the column to.
     * @param string $column The name of the new column.
     * @param string $type The data type of the new column.
     * @return void
     */
    public function addColumn($table, $column, $type)
    {
        try {
            $sql = "ALTER TABLE $table ADD COLUMN $column $type";
            $this->pdo->exec($sql);
        } catch (PDOException $e) {
            $this->errorHandler->handleError(
                "SQL Error Occurred",
                null,
                [
                    "Code" => $e->getCode(),
                    "Error" => $e->getMessage(),
                    "Table" => $table,
                    "SQL Command" => $sql
                ],
                "ERROR"
            );
        }
    }

    /**
     * Drops the specified column from the specified table.
     *
     * @param string $table The name of the table to drop the column from.
     * @param string $column The name of the column to drop.
     * @return void
     */
    public function dropColumn($table, $column)
    {
        try {
            $sql = "ALTER TABLE $table DROP COLUMN $column";
            $this->pdo->exec($sql);
        } catch (PDOException $e) {
            $this->errorHandler->handleError(
                "SQL Error Occurred",
                null,
                [
                    "Code" => $e->getCode(),
                    "Error" => $e->getMessage(),
                    "Table" => $table,
                    "SQL Command" => $sql
                ],
                "ERROR"
            );
        }
    }


    /**
     * Adds an index to the specified table and column.
     *
     * @param string $table The name of the table to add the index to.
     * @param string $column The name of the column to add the index to.
     * @param bool $unique Whether the index should be unique or not.
     * @return void
     */
    public function addIndex($table, $column, $unique = false)
    {
        try {
            $index_name = "idx_" . $table . "_" . $column;
            $unique_sql = $unique ? "UNIQUE" : "";
            $sql = "ALTER TABLE $table ADD $unique_sql INDEX $index_name ($column)";
            $this->pdo->exec($sql);
        } catch (PDOException $e) {
            $this->errorHandler->handleError(
                "SQL Error Occurred",
                null,
                [
                    "Code" => $e->getCode(),
                    "Error" => $e->getMessage(),
                    "Table" => $table,
                    "SQL Command" => $sql
                ],
                "ERROR"
            );
        }
    }

    /**
     * Drops the index from the specified table and column.
     *
     * @param string $table The name of the table to drop the index from.
     * @param string $column The name of the column to drop the index from.
     * @return void
     */
    public function dropIndex($table, $column)
    {
        try {
            $index_name = "idx_" . $table . "_" . $column;
            $sql = "ALTER TABLE $table DROP INDEX $index_name";
            $this->pdo->exec($sql);
        } catch (PDOException $e) {
            $this->errorHandler->handleError(
                "SQL Error Occurred",
                null,
                [
                    "Code" => $e->getCode(),
                    "Error" => $e->getMessage(),
                    "Table" => $table,
                    "SQL Command" => $sql
                ],
                "ERROR"
            );
        }
    }



    /**
     * Adds a foreign key constraint to a table.
     *
     * @param string $table The name of the table.
     * @param string $column The name of the column that will have the foreign key constraint.
     * @param string $refTable The name of the referenced table.
     * @param string $refColumn The name of the referenced column.
     * @param string $onDelete The action to take when the referenced row is deleted. Defaults to 'CASCADE'.
     * @param string $onUpdate The action to take when the referenced row is updated. Defaults to 'CASCADE'.
     *
     * @return void
     */
    public function addForeignKey($table, $column, $refTable, $refColumn, $onDelete = 'CASCADE', $onUpdate = 'CASCADE')
    {
        try {
            $sql = "ALTER TABLE $table ADD FOREIGN KEY ($column) REFERENCES $refTable ($refColumn) ON DELETE $onDelete ON UPDATE $onUpdate";
            $this->pdo->exec($sql);
        } catch (PDOException $e) {
            $this->errorHandler->handleError(
                "SQL Error Occurred",
                null,
                [
                    "Code" => $e->getCode(),
                    "Error" => $e->getMessage(),
                    "Table" => $table,
                    "SQL Command" => $sql
                ],
                "ERROR"
            );
        }
    }

    /**
     * Drops a foreign key constraint from a table.
     *
     * @param string $table The name of the table.
     * @param string $column The name of the column that has the foreign key constraint.
     *
     * @return void
     */
    public function dropForeignKey($table, $column)
    {
        try {
            $sql = "ALTER TABLE $table DROP FOREIGN KEY $column";
            $this->pdo->exec($sql);
        } catch (PDOException $e) {
            $this->errorHandler->handleError(
                "SQL Error Occurred",
                null,
                [
                    "Code" => $e->getCode(),
                    "Error" => $e->getMessage(),
                    "Table" => $table,
                    "SQL Command" => $sql
                ],
                "ERROR"
            );
        }
    }


    /**
     * Disables foreign key constraint checks for the current connection.
     *
     * @return void
     */
    public function disableForeignKeyChecks()
    {
        try {
            $this->pdo->exec('SET FOREIGN_KEY_CHECKS=0');
        } catch (PDOException $e) {
            $this->errorHandler->handleError(
                "SQL Error Occurred",
                null,
                [
                    "Code" => $e->getCode(),
                    "Error" => $e->getMessage(),
                    "SQL Command" => 'SET FOREIGN_KEY_CHECKS=0'
                ],
                "CRITICAL"
            );
        }
    }

    /**
     * Enables foreign key constraint checks for the current connection.
     *
     * @return void
     */
    public function enableForeignKeyChecks()
    {
        try {
            $this->pdo->exec('SET FOREIGN_KE
            Y_CHECKS=1');
            
        } catch (PDOException $e) {

            $this->errorHandler->handleError(
                "SQL Error Occurred",
                null,
                [
                    "Code" => $e->getCode(),
                    "Error" => $e->getMessage(),
                    "SQL Command" => 'SET FOREIGN_KEY_CHECKS=1'
                ],
                "CRITICAL"
            );
        }
    }
}
