<?php

namespace Nxp\Core\Database;

class Table
{
    private $pdo;
    private $tableName;

    public function __construct($tableName)
    {
        $this->pdo = Database::connect();
        $this->tableName = $tableName;
    }

    public function create($columns)
    {
        $fields = implode(", ", $columns);
        $sql = "CREATE TABLE IF NOT EXISTS {$this->tableName} ($fields)";
        return $this->pdo->exec($sql);
    }

    public function addColumn($columnName, $columnDefinition)
    {
        $sql = "ALTER TABLE {$this->tableName} ADD COLUMN {$columnName} {$columnDefinition}";
        return $this->pdo->exec($sql);
    }

    public function modifyColumn($columnName, $columnDefinition)
    {
        $sql = "ALTER TABLE {$this->tableName} MODIFY COLUMN {$columnName} {$columnDefinition}";
        return $this->pdo->exec($sql);
    }

    public function dropColumn($columnName)
    {
        $sql = "ALTER TABLE {$this->tableName} DROP COLUMN {$columnName}";
        return $this->pdo->exec($sql);
    }

    // You can add more table-related methods as needed.
}
