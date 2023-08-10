<?php
namespace Nxp\Core\Database\Internal;


use Exception;
use PDOException;
use Nxp\Core\Utils\Service\Container;
use Nxp\Core\Utils\Error\ErrorFactory;
use Nxp\Core\Utils\Navigation\Redirects;

final class InternalBatch
{
    private $pdo;
    private $container;
    private $errorHandler;

    public function __construct(Container $container = null)
    {
        $this->container = $container;
        $this->pdo = $container->get('pdo');

        $factory = new ErrorFactory($container);
        $this->errorHandler = $factory->createErrorHandler();
    }

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
                $this->errorHandler->handleError(
                    "SQL Error Occurred",
                    null,
                    ["Message" => "Failed to prepare SQL statement", "Table" => $table, "SQL Command" => $sql],
                    "CRITICAL"
                );
                throw new Exception("Error within SQL Command. Table: $table\\n\\nSQL Command $sql");
            }
            $stmt->execute($params);
            return $stmt->rowCount();
        } catch (PDOException $e) {
            $this->errorHandler->handleError(
                "SQL Error Occurred",
                $e,
                ["Message" => "An error occurred with the SQL query", "Table" => $table, "SQL Command" => $sql],
                "CRITICAL"
            );
            Redirects::redirectToPreviousPageOrHome();
        }
    }

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
                $this->errorHandler->handleError(
                    "SQL Error Occurred",
                    null,
                    ["Message" => "Failed to prepare SQL statement", "Table" => $table, "SQL Command" => $sql],
                    "CRITICAL"
                );
                Redirects::redirectToPreviousPageOrHome();
            }
            $stmt->execute($params);
            return $stmt->rowCount();
        } catch (PDOException $e) {
            $this->errorHandler->handleError(
                "SQL Error Occurred",
                $e,
                ["Message" => "An error occurred with the SQL query", "Table" => $table, "SQL Command" => $sql],
                "CRITICAL"
            );
            Redirects::redirectToPreviousPageOrHome();
        }
    }

    public function deleteBatch($table, $column, $values)
    {
        try {
            // Build the DELETE query with an IN statement for the values to delete.
            $sql = "DELETE FROM $table WHERE $column IN (" . implode(', ', array_fill(0, count($values), '?')) . ")";

            // Execute the delete query.
            $stmt = $this->pdo->prepare($sql);
            if (!$stmt) {
                $this->errorHandler->handleError(
                    "SQL Error Occurred",
                    null,
                    ["Message" => "Failed to prepare SQL statement", "Table" => $table, "SQL Command" => $sql],
                    "CRITICAL"
                );
                Redirects::redirectToPreviousPageOrHome();
            }
            $stmt->execute($values);
            return $stmt->rowCount();
        } catch (PDOException $e) {
            $this->errorHandler->handleError(
                "SQL Error Occurred",
                $e,
                ["Message" => "An error occurred with the SQL query", "Table" => $table, "SQL Command" => $sql],
                "CRITICAL"
            );
            Redirects::redirectToPreviousPageOrHome();
        }
    }
}