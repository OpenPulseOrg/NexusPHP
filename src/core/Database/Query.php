<?php

namespace Nxp\Core\Database;

use PDO;

class Query
{
    private $pdo;
    private $stmt;
    private $sql = '';
    private $bindings = [];

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    public function select($table, $columns = '*')
    {
        $this->sql = "SELECT {$columns} FROM {$table} ";
        return $this;
    }

    public function where($condition, $bindings = [])
    {
        $this->sql .= (stripos(trim($this->sql), 'where') === false) ? "WHERE {$condition} " : "AND {$condition} ";
        $this->bindings = array_merge($this->bindings, $bindings);
        return $this;
    }

    public function orWhere($condition, $bindings = [])
    {
        $this->sql .= "OR {$condition} ";
        $this->bindings = array_merge($this->bindings, $bindings);
        return $this;
    }

    public function insert($table, $data)
    {
        $fields = implode(", ", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));
        $this->sql = "INSERT INTO {$table} ($fields) VALUES ($placeholders)";
        $this->bindings = $data;
        return $this->execute();
    }

    public function update($table, $data)
    {
        $fields = "";
        foreach ($data as $key => $value) {
            $fields .= "$key = :$key, ";
        }
        $fields = rtrim($fields, ', ');
        $this->sql .= "UPDATE {$table} SET {$fields} ";
        $this->bindings = array_merge($this->bindings, $data);
        return $this;
    }

    public function delete($table)
    {
        $this->sql = "DELETE FROM {$table} ";
        return $this;
    }

    public function execute()
    {
        $this->prepare($this->sql);
        foreach ($this->bindings as $key => $value) {
            $this->bind($key, $value);
        }
        return $this->stmt->execute();
    }

    private function prepare($sql)
    {
        $this->stmt = $this->pdo->prepare($sql);
    }

    public function bind($param, $value, $type = null)
    {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param, $value, $type);
    }

    public function fetchAll()
    {
        return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function fetch()
    {
        return $this->stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function fetchFirst()
    {
        $result = $this->stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            return array_values($result)[0];
        }
        return null;
    }

    public function fetchColumn($columnNumber = 0)
    {
        return $this->stmt->fetchColumn($columnNumber);
    }

    public function rowCount()
    {
        return $this->stmt->rowCount();
    }

    public function lastInsertId()
    {
        return $this->pdo->lastInsertId();
    }

    /** 
     * Raw SQL Execution
     * 
     * @param string $sql The raw SQL query.
     * @param array $bindings The bindings for the query.
     * @return bool The result of the query execution.
     */
    public function rawSQLExecution($sql, $bindings = [])
    {
        $this->prepare($sql);
        foreach ($bindings as $param => $value) {
            $this->bind($param, $value);
        }
        return $this->execute();
    }

    /** 
     * JOIN operations
     * 
     * @param string $table The table to join.
     * @param string $condition The JOIN condition.
     * @param string $type The type of join (INNER, LEFT, RIGHT, etc.).
     * @return void
     */
    public function join($table, $condition, $type = 'INNER')
    {
        $this->sql .= " $type JOIN $table ON $condition";
        return $this;
    }

    /** 
     * ORDER BY clause
     * 
     * @param string $column The column to order by.
     * @param string $direction The direction of ordering (ASC or DESC).
     * @return void
     */
    public function orderBy($column, $direction = 'ASC')
    {
        $this->sql .= " ORDER BY $column $direction";
        return $this;
    }

    /** 
     * LIMIT clause
     * 
     * @param int $number The number of rows to limit.
     * @return $this
     */
    public function limit($number)
    {
        $this->sql .= " LIMIT $number";
        return $this;    
    }
    
    /** 
     * OFFSET clause
     * 
     * @param int $number The offset number.
     * @return $this
     */
    public function offset($number)
    {
        $this->sql .= " OFFSET $number";
        return $this;
    }

    /** 
     * GROUP BY clause
     * 
     * @param string $column The column to group by.
     * @return void
     */
    public function groupBy($column)
    {
        $this->sql .= " GROUP BY $column";
        return $this;
    }

    public function whereIn($column, $values = [])
    {
        $placeholders = implode(', ', array_fill(0, count($values), '?'));
        $this->sql .= "$column IN ($placeholders) ";
        $this->bindings = array_merge($this->bindings, $values);
        return $this;
    }

    public function having($condition)
    {
        $this->sql .= "HAVING $condition ";
        return $this;
    }

    public function distinct()
    {
        $this->sql = "SELECT DISTINCT " . substr($this->sql, 7);
        return $this;
    }

    public function count($column = '*')
    {
        $this->sql = "SELECT COUNT($column) " . substr($this->sql, strpos($this->sql, 'FROM'));
        return $this->fetchFirst();
    }
    public function between($column, $value1, $value2)
    {
        $this->sql .= "$column BETWEEN ? AND ? ";
        $this->bindings[] = $value1;
        $this->bindings[] = $value2;
        return $this;
    }
    public function like($column, $pattern)
    {
        $this->sql .= "$column LIKE ? ";
        $this->bindings[] = $pattern;
        return $this;
    }
}
