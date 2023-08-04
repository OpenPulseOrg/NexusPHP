<?php

namespace Nxp\Core\Database\Internal;

use Nxp\Core\Database\Factories\Query;
use Nxp\Core\Security\Logging\Logger;
use Nxp\Core\Utils\Navigation\Redirects;
use Nxp\Core\Utils\Service\Container;
use PDOException;

/**
 * Batch class for performing batch operations on a database, such as batch updates, inserts, and deletes.
 *
 * @package Nxp\Core\Database
 */
final class InternalBatch
{
    private $pdo;
    private $container;
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

        $queryFactory = new Query($container);
        $this->logger = new Logger($queryFactory);
    }

    /**
     * Updates multiple rows in a database table using a batch update.
     *
     * @param string $table The name of the database table to update.
     * @param array $data An array of associative arrays representing the data to update.
     * @param string $column The name of the primary key column in the database table.
     * @return int The number of rows that were updated.
     */
    public function updateBatch($table, $data, $column)
    {
        try {
            // Start the update query.
            $sql = "UPDATE $table SET ";

            // Build the SET clause with a CASE statement for each value.
            $setClauses = [];
            $params = [];
            foreach ($data as $value) {
                $setClauses[] = "$column = CASE ";
                foreach ($value as $key => $val) {
                    $setClauses[] = "WHEN $key = ? THEN ?";
                    $params[] = $key;
                    $params[] = $val;
                }
                $setClauses[] = "ELSE $column END";
            }
            $sql .= implode(', ', $setClauses);

            // Build the WHERE clause with an IN statement for all keys.
            $keys = array_keys(reset($data));
            $whereClause = implode(', ', array_fill(0, count($keys), '?'));
            $sql .= " WHERE $column IN ($whereClause)";
            $params = array_merge($params, $keys);

            // Execute the update query.
            $stmt = $this->pdo->prepare($sql);
            if (!$stmt) {
                 $this->logger->log("CRITICAL", "SQL Error Occurred", [
                    "Message" => "Failed to prepare SQL statement",
                    "Table" => $table,
                    "SQL Command" => $sql,
                ]);
                Redirects::redirectToPreviousPageOrHome();
            }
            $stmt->execute($params);
            return $stmt->rowCount();
        } catch (PDOException $e) {
             $this->logger->log("CRITICAL", "SQL Error Occurred", [
                "Message" => "An error occurred with the SQL query",
                "Table" => $table,
                "SQL Command" => $sql,
                "Error" => $e->getMessage(),
                "Code" => $e->getCode()
            ]);
            Redirects::redirectToPreviousPageOrHome();
        }
    }


    /**
     * Inserts multiple rows into a database table using a batch insert.
     *
     * @param string $table The name of the database table to insert data into.
     * @param array $data An array of associative arrays representing the data to insert.
     * @return int The number of rows that were inserted.
     */
    public function insertBatch($table, $data)
    {
        try {
            // Build the INSERT query with a VALUES clause for each row.
            $sql = "INSERT INTO $table (" . implode(', ', array_keys(reset($data))) . ") VALUES ";
            $valueClauses = [];
            $params = [];
            foreach ($data as $row) {
                $valueClauses[] = '(' . implode(', ', array_fill(0, count($row), '?')) . ')';
                $params = array_merge($params, array_values($row));
            }
            $sql .= implode(', ', $valueClauses);

            // Execute the insert query.
            $stmt = $this->pdo->prepare($sql);
            if (!$stmt) {
                 $this->logger->log("CRITICAL", "SQL Error Occurred", [
                    "Message" => "Failed to prepare SQL statement",
                    "Table" => $table,
                    "SQL Command" => $sql,
                ]);
                Redirects::redirectToPreviousPageOrHome();
            }
            $stmt->execute($params);
            return $stmt->rowCount();
        } catch (PDOException $e) {
             $this->logger->log("CRITICAL", "SQL Error Occurred", [
                "Message" => "An error occurred with the SQL query",
                "Table" => $table,
                "SQL Command" => $sql,
                "Error" => $e->getMessage(),
                "Code" => $e->getCode()
            ]);
            Redirects::redirectToPreviousPageOrHome();
        }
    }

    /**
     * Deletes multiple rows from a database table using a batch delete.
     *
     * @param string $table The name of the database table to delete data from.
     * @param string $column The name of the column to filter by.
     * @param array $values An array of values to delete.
     * @return int The number of rows that were deleted.
     */
    public function deleteBatch($table, $column, $values)
    {
        try {
            // Build the DELETE query with an IN statement for the values to delete.
            $inClause = implode(', ', array_fill(0, count($values), '?'));
            $sql = "DELETE FROM $table WHERE $column IN ($inClause)";

            // Execute the delete query.
            $stmt = $this->pdo->prepare($sql);
            if (!$stmt) {
                 $this->logger->log("CRITICAL", "SQL Error Occurred", [
                    "Message" => "Failed to prepare SQL statement",
                    "Table" => $table,
                    "SQL Command" => $sql,
                ]);
                Redirects::redirectToPreviousPageOrHome();
            }
            $stmt->execute($values);
            return $stmt->rowCount();
        } catch (PDOException $e) {
             $this->logger->log("CRITICAL", "SQL Error Occurred", [
                "Message" => "An error occurred with the SQL query",
                "Table" => $table,
                "SQL Command" => $sql,
                "Error" => $e->getMessage(),
                "Code" => $e->getCode()
            ]);
            Redirects::redirectToPreviousPageOrHome();
        }
    }
}
