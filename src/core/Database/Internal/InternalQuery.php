<?php

namespace Nxp\Core\Database\Internal;

use Exception;
use Nxp\Core\Utils\Error\ErrorFactory;
use Nxp\Core\Utils\Navigation\Redirects;
use Nxp\Core\Utils\Service\Container;
use PDO;
use PDOException;

/**
 * Query class for building and executing SQL queries with various operations like SELECT, INSERT, UPDATE, and DELETE.
 *
 * @package Nxp\Core\Database
 */
final class InternalQuery
{
    private $container;
    private $pdo;
    private $table;
    private $errorHandler;
    private $select = '*';
    private $where = '';
    private $params = [];
    private $orderBy = '';
    private $limit = '';
    private $join = '';

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
     * Sets the columns to select in the query.
     *
     * @param string|array $columns The columns to select.
     * @return $this The QueryBuilder object for method chaining.
     */
    public function select($columns = '*')
    {
        if (is_array($columns)) {
            $this->select = implode(',', $columns);
        } else {
            $this->select = $columns;
        }
        return $this;
    }

    /**
     * Sets the table to query in the database.
     *
     * @param string $table The name of the table to query.
     * @return $this The QueryBuilder object for method chaining.
     */
    public function table($table)
    {
        $this->table = $table;
        return $this;
    }

    /**
     * Sets the WHERE clause for the query.
     *
     * @param string $where The WHERE clause of the query.
     * @param array $params An array of parameter values to bind to the query.
     * @return $this The QueryBuilder object for method chaining.
     */
    /**
     * Sets the WHERE clause for the query.
     *
     * @param array|string $where The WHERE clause of the query. Can be provided as an associative array or a string.
     * @param array $params An array of parameter values to bind to the query.
     * @return $this The QueryBuilder object for method chaining.
     */
    public function where($where, $params = [])
    {
        if (is_array($where)) {
            $conditions = [];
            foreach ($where as $column => $value) {
                $conditions[] = "$column = :$column";
                $this->params[":$column"] = $value;
            }
            $this->where = implode(' AND ', $conditions);
        } else {
            $this->where = $where;
            $this->params = $params;
        }
        return $this;
    }



    /**
     * Sets the ORDER BY clause for the query.
     *
     * @param string $column The name of the column to order by.
     * @param string $direction The direction to order by, either 'ASC' or 'DESC'.
     * @return $this The QueryBuilder object for method chaining.
     */
    public function orderBy($column, $direction = 'ASC')
    {
        $this->orderBy = "ORDER BY $column $direction";
        return $this;
    }

    /**
     * Sets the LIMIT clause for the query.
     *
     * @param int $limit The maximum number of rows to return.
     * @return $this The QueryBuilder object for method chaining.
     */
    public function limit($limit)
    {
        $this->limit = "LIMIT $limit";
        return $this;
    }

    /**
     * Adds an INNER JOIN clause to the query.
     *
     * @param string $table The name of the table to join.
     * @param string $condition The join condition to use.
     * @return $this The QueryBuilder object for method chaining.
     */
    public function join($table, $condition)
    {
        $this->join .= " INNER JOIN $table ON $condition";
        return $this;
    }

    /**
     * Executes the query and returns the result set.
     *
     * @return array An array of rows returned by the query.
     */
    public function get()
    {
        if (empty($this->table)) {
            $this->errorHandler->handleError(
                "SQL Error Occurred",
                null,
                ["Message" => "No table specified for GET query"],
                "ERROR"
            );

            Redirects::redirectToPreviousPageOrHome();
        }
        $sql = "SELECT $this->select FROM $this->table";
        if (!empty($this->join)) {
            $sql .= $this->join;
        }
        if (!empty($this->where)) {
            $sql .= " WHERE $this->where";
        }
        if (!empty($this->orderBy)) {
            $sql .= " $this->orderBy";
        }
        if (!empty($this->limit)) {
            $sql .= " $this->limit";
        }
        try {
            $stmt = $this->pdo->prepare($sql);
            $this->errorHandler->handleError(
                "SQL Error Occurred",
                null,
                ["Message" => "Failed to prepare SQL statement", "Table" => $this->table, "SQL Command" => $sql],
                "ERROR"
            );
            $stmt->execute($this->params);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            $this->errorHandler->handleError(
                "SQL Error Occurred",
                $e,
                ["Message" => "An error occurred with the SQL query", "Table" => $this->table, "SQL Command" => $sql],
                "CRITICAL"
            );
        }
    }



    /**
     * Returns the first row of the query result, or null if the result is empty.
     *
     * @return mixed The first row of the query result, or null if the result is empty.
     */
    public function first()
    {
        $result = $this->limit(1)->get();
        return !empty($result) ? $result[0] : null;
    }

    /**
     * Returns the number of rows that match the current query conditions.
     * 
     * @return int The number of rows that match the current query conditions.
     */
    public function count()
    {
        if (empty($this->table)) {
            $this->errorHandler->handleError(
                "SQL Error Occurred",
                null,
                ["Message" => "No table specified for GET query"],
                "ERROR"
            );
        }
        $sql = "SELECT COUNT(*) FROM $this->table";
        if (!empty($this->where)) {
            $sql .= " WHERE $this->where";
        }
        try {
            $stmt = $this->pdo->prepare($sql);
            $this->errorHandler->handleError(
                "SQL Error Occurred",
                null,
                ["Message" => "Failed to prepare SQL statement", "Table" => $this->table, "SQL Command" => $sql],
                "ERROR"
            );
            $stmt->execute($this->params);
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            $this->errorHandler->handleError(
                "SQL Error Occurred",
                $e,
                ["Message" => "An error occurred with the SQL query", "Table" => $this->table, "SQL Command" => $sql],
                "CRITICAL"
            );
        }
    }

    /**
     * Inserts a new row into the database table with the given data and returns the new row's ID.
     *
     * @param array $data An associative array of column names and their values for the new row.
     * @return int|string The ID of the new row that was inserted.
     */
    public function insert($data)
    {
        try {
            if (empty($this->table)) {
                throw new Exception("Table not specified");
            }

            // Add the current timestamp to the $data array if "created_on" is a column
            if (array_key_exists('created_on', $data)) {
                $data['created_on'] = date('Y-m-d H:i:s'); // Current timestamp in 'YYYY-MM-DD HH:MM:SS' format
            }

            $keys = array_keys($data);
            $values = array_values($data);
            $placeholders = array_fill(0, count($keys), '?');
            $sql = "INSERT INTO $this->table (" . implode(',', $keys) . ") VALUES (" . implode(',', $placeholders) . ")";
            $stmt = $this->pdo->prepare($sql);

            $stmt->execute($values);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row;
        } catch (PDOException $e) {
            throw new PDOException($e);
        }
    }



    /**
     * Updates one or more rows in the database table with the given data and WHERE clause.
     *
     * @param array $data An associative array of column names and their new values for the updated row(s).
     * @return bool Whether the update was successful.
     */
    public function update($data)
    {
        try {
            if (empty($this->table)) {
                $this->errorHandler->handleError(
                    "SQL Error Occurred",
                    null,
                    ["Message" => "No table specified for GET query"],
                    "ERROR"
                );
            }
            $set = [];
            $values = [];
            foreach ($data as $key => $value) {
                $set[] = "$key = :$key";
                $values[":$key"] = $value;
            }
            $sql = "UPDATE $this->table SET " . implode(',', $set);
            if (!empty($this->where)) {
                $sql .= " WHERE $this->where";
            }
            $stmt = $this->pdo->prepare($sql);
            if (!$stmt) {
                $this->errorHandler->handleError(
                    "SQL Error Occurred",
                    null,
                    ["Message" => "Failed to prepare SQL statement", "Table" => $this->table, "SQL Command" => $sql],
                    "ERROR"
                );
            }
            $values = array_merge($values, $this->params);
            $stmt->execute($values);
            return true;
        } catch (PDOException $e) {
            $this->errorHandler->handleError(
                "SQL Error Occurred",
                $e,
                ["Message" => "An error occurred with the SQL query", "Table" => $this->table, "SQL Command" => $sql],
                "CRITICAL"
            );
            throw new Exception($e);
        }
        return false;
    }




    /**
     * Deletes one or more rows from the database table with the given WHERE clause.
     *
     * @return bool Whether the deletion was successful.
     */
    public function delete()
    {
        try {
            if (empty($this->table)) {
                $this->errorHandler->handleError(
                    "SQL Error Occurred",
                    null,
                    ["Message" => "No table specified for GET query"],
                    "ERROR"
                );
            }
            $sql = "DELETE FROM $this->table";
            if (!empty($this->where)) {
                $sql .= " WHERE $this->where";
            }
            $stmt = $this->pdo->prepare($sql);
            if (!$stmt) {
                $this->errorHandler->handleError(
                    "SQL Error Occurred",
                    null,
                    ["Message" => "Failed to prepare SQL statement", "Table" => $this->table, "SQL Command" => $sql],
                    "ERROR"
                );
            }
            return $stmt->execute($this->params);
        } catch (PDOException $e) {
            $this->errorHandler->handleError(
                "SQL Error Occurred",
                $e,
                ["Message" => "An error occurred with the SQL query", "Table" => $this->table, "SQL Command" => $sql],
                "CRITICAL"
            );
        }
    }
}
