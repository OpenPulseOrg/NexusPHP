<?php

namespace Database;

use Nxp\Core\Config\ConfigHandler;
use PDO;
use QuestionHelper;

class Migration
{

    private $databaseType = "";

    public function __construct()
    {
        $this->databaseType = ConfigHandler::get("database", "DATABASE_TYPE");
    }

    public function migrate()
    {
        global $db;

        $questionHelper = new QuestionHelper();

        $tableCreationSQL = "
            CREATE TABLE IF NOT EXISTS migrations (
                id SERIAL PRIMARY KEY,
                migration_name VARCHAR(255),
                executed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                )
        ";

        $db->exec($tableCreationSQL);


        // Get the list of migration files
        $migrationFiles = glob(__DIR__ . '/../../../../../migrations/*.php');

        // Retrieve the list of executed migrations
        $executedMigrations = $this->getExecutedMigrations();

        if (empty($migrationFiles)) {
            $questionHelper->output("No migration files found.\n", "red");
            return;
        }

        // Sort the migration files in ascending order based on the timestamp in the file name
        usort($migrationFiles, function ($a, $b) {
            $timestampA = $this->getMigrationTimestamp($a);
            $timestampB = $this->getMigrationTimestamp($b);
            return $timestampA <=> $timestampB;
        });

        // Execute each pending migration
        foreach ($migrationFiles as $migrationFile) {
            // Extract the migration name from the file name
            $migrationName = basename($migrationFile, '.php');

            if (in_array($migrationName, $executedMigrations)) {
                $questionHelper->output("Skipping migration: $migrationName (already executed)\n", "blue");
                continue;
            }

            // Extract the class name from the migration name
            $migrationClass = substr($migrationName, strpos($migrationName, '_') + 1);

            // Create an instance of the migration class
            $migration = new $migrationClass($db);

            // Execute the migration
            $migration->up();

            // Insert the executed migration into the 'migrations' table
            $db->exec("INSERT INTO migrations (migration_name) VALUES ('$migrationName')");

            $questionHelper->output("Executed migration: $migrationName\n", "yellow");
        }

        $questionHelper->output("All migrations executed successfully.\n", "green");
    }

    public function rollback()
    {
        global $db;

        $questionHelper = new QuestionHelper();

        // Retrieve the last executed migration
        $lastMigration = $db->query("SELECT * FROM migrations ORDER BY id DESC LIMIT 1")->fetch(PDO::FETCH_ASSOC);

        if (!$lastMigration) {
            $questionHelper->output("No executed migrations found.\n", "yellow");
            return;
        }

        // Get the migration file name
        $migrationName = $lastMigration['migration_name'];

        // Extract the class name from the migration name
        $migrationClass = substr($migrationName, strpos($migrationName, '_') + 1);

        // Check if the class exists
        if (!class_exists($migrationClass)) {
            $questionHelper->output("Migration class $migrationClass does not exist.\n", "red");
            return;
        }

        // Create an instance of the migration class
        $migration = new $migrationClass($db);

        // Rollback the migration
        $migration->down();

        // Remove the last executed migration from the 'migrations' table
        $db->exec("DELETE FROM migrations WHERE id = {$lastMigration['id']}");

        $questionHelper->output("Rolled back migration: $migrationName\n", "green");
    }

    public function generate()
    {
        $questionHelper = new QuestionHelper();

        $migrationClassName = $questionHelper->ask("Enter Class Name: ");

        $timestamp = date('YmdHis');
        $migrationName = $timestamp . '_' . $migrationClassName;

        // Ask for the table information
        $tableInfo = [];
        while (true) {
            $tableName =
                $questionHelper->askWithInfo(
                    "Enter Table Name:",
                    "Leave empty to finish"
                );
            if (empty($tableName)) {
                break;
            }

            $columns = [];
            while (true) {
                $columnName = $questionHelper->askWithInfo(
                    "Enter Column Name:",
                    "Leave empty to finish defining columns for this table. ID and created_on columns will be generated automatically"
                );

                if (empty($columnName)) {
                    break;
                }

                $columnType = $questionHelper->ask("Enter Column Type (e.g., VARCHAR, INT, TEXT): ");
                $columnLength = $questionHelper->ask("Enter Column Length: ");
                // Add more column parameters as needed

                $columns[] = [
                    'name' => $columnName,
                    'type' => $columnType,
                    'length' => $columnLength,
                    // Add more column parameters as needed
                ];
            }

            $tableInfo[] = [
                'name' => $tableName,
                'columns' => $columns,
            ];
        }

        // // Generate the migration code using the collected table information
        $migrationUpCode = $this->generateUpCode($migrationClassName, $tableInfo);
        $migrationDownCode = $this->generateDownCode($migrationClassName, $tableInfo);
        // $migrationCode = "working";
        $migrationTemplate = <<<PHP
    <?php
    
    class $migrationClassName
    {
        protected \$db;

        public function __construct(PDO \$db)
        {
            \$this->db = \$db;
        }

        public function up()
        {
            $migrationUpCode
        }
    
        public function down()
        {
            $migrationDownCode
        }
    }
    PHP;

        // Save the migration file
        $filename = __DIR__ . '/../../../../../migrations/' . $migrationName . '.php';

        if (!file_exists($filename)) {
            file_put_contents($filename, $migrationTemplate);
            $questionHelper->output("Migration file '$migrationName.php' generated successfully\n", "green");
        } else {
            $questionHelper->output("Migration file '$migrationName.php' already exists.\n", "red");
        }
    }



    // Retrieve the list of executed migrations
    private function getExecutedMigrations()
    {
        global $db;
        $query = $db->query("SELECT migration_name FROM migrations");
        return $query->fetchAll(PDO::FETCH_COLUMN);
    }

    // Function to extract the migration timestamp from the migration file
    private function getMigrationTimestamp($migrationFile)
    {
        $fileName = basename($migrationFile, '.php');
        return substr($fileName, 0, 14);
    }

    private function generateUpCode($migrationClassName, $tableInfo)
    {
        $migrationCode = "";

        foreach ($tableInfo as $table) {
            $tableName = $table['name'];
            $columns = $table['columns'];

            $columnDefinitions = "";
            foreach ($columns as $column) {
                $columnName = $column['name'];
                $columnType = $column['type'];
                $columnLength = $column['length'];

                $columnDefinition = "$columnName $columnType";
                if (!empty($columnLength)) {
                    $columnDefinition .= "($columnLength)";
                }

                $columnDefinitions .= "    $columnDefinition,\n";
            }
            $migrationCode .= "
                \$this->db->exec(\"
                    CREATE TABLE IF NOT EXISTS $tableName (
                        id SERIAL PRIMARY KEY,
                        $columnDefinitions                
                        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                    )
                \");
                ";
        }

        return $migrationCode;
    }

    private function generateDownCode($migrationClassName, $tableInfo)
    {
        $migrationCode = "";

        foreach ($tableInfo as $table) {
            $tableName = $table['name'];

            $migrationCode .= "
        \$this->db->exec(\"DROP TABLE IF EXISTS $tableName\");
        ";
        }

        return $migrationCode;
    }

    public function convertSqlToMigrationFile()
    {
        $questionHelper = new QuestionHelper();

        $sqlFilePath = $questionHelper->ask("Full SQL File Path: ");
        $sqlFilePath = trim($sqlFilePath, "\"'");

        if (!file_exists($sqlFilePath)) {
            $questionHelper->output("Error: SQL file not found at '$sqlFilePath'\n", "red");
            return;
        }

        $sqlContent = file_get_contents($sqlFilePath);
        $migrationClassName = 'Convert' . date('YmdHis');

        if ($this->databaseType === 'mysql') {
            $convertedMigrationCode = $this->convertSqlForMySQL($sqlContent);
        } else {
            $convertedMigrationCode = $this->convertSqlForPostgreSQL($sqlContent);
        }

        $migrationTemplate = <<<EOF
    <?php
    
    class $migrationClassName
    {
        protected \$db;
    
        public function __construct(PDO \$db)
        {
            \$this->db = \$db;
        }
    
        public function up()
        {
            try {
                \$this->db->exec(<<<EOT
    $convertedMigrationCode
    EOT
                );
            } catch (PDOException \$e) {
                echo "Error while executing migration: " . \$e->getMessage();
            }
        }
    
        public function down()
        {
        }
    }
    
    EOF;

        $timestamp = date('YmdHis');
        $migrationName = $timestamp . '_' . $migrationClassName;
        $filename = __DIR__ . '/../../../../../migrations/' . $migrationName . '.php';

        if (!file_exists($filename)) {
            file_put_contents($filename, $migrationTemplate);
            $questionHelper->output("Migration file '$migrationName.php' generated successfully\n", "green");
        } else {
            $questionHelper->output("Migration file '$migrationName.php' already exists.\n", "red");
        }
    }


    private function convertSqlForMySQL($sqlContent)
    {
        $convertedMysqlCode = str_ireplace('SERIAL', 'INT AUTO_INCREMENT', $sqlContent);
        $convertedMysqlCode = str_replace('"', '`', $convertedMysqlCode);
        $convertedMysqlCode = preg_replace("/COMMENT '(.+?)'/", "COMMENT '$1'", $convertedMysqlCode);

        return $convertedMysqlCode;
    }

    private function convertSqlForPostgreSQL($sqlContent)
    {
        // Replace INT(11) with INTEGER for PostgreSQL
        $convertedPgsqlCode = str_ireplace('int(11) NOT NULL AUTO_INCREMENT', 'SERIAL PRIMARY KEY', $sqlContent);
        $convertedPgsqlCode = str_ireplace('int(1)', 'INTEGER', $convertedPgsqlCode);
    
        // Replace varchar(255) and tinytext with TEXT for PostgreSQL
        $convertedPgsqlCode = str_ireplace(['varchar(255)', 'tinytext'], 'TEXT', $convertedPgsqlCode);
        
        // Replace backticks (MySQL) with nothing
        $convertedPgsqlCode = str_replace('`', '', $convertedPgsqlCode);
        
        // Remove MySQL table options
        $convertedPgsqlCode = preg_replace('/\sENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;/', ';', $convertedPgsqlCode);
    
        // Extract column comments and convert to PostgreSQL COMMENT ON COLUMN syntax
        preg_match_all("/COMMENT '(.*?)' ON (.*?)$/m", $convertedPgsqlCode, $matches);
        
        $comments = '';
        for($i = 0; $i < count($matches[0]); $i++) {
            $comments .= "\nCOMMENT ON COLUMN " . trim($matches[2][$i]) . " IS '" . trim($matches[1][$i]) . "';";
            $convertedPgsqlCode = str_replace($matches[0][$i], '', $convertedPgsqlCode); // Remove the original MySQL comment
        }
    
        return $convertedPgsqlCode . $comments;
    }
    
    
    
}
