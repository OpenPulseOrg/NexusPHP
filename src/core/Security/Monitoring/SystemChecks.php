<?php

namespace Nxp\Core\Security\Monitoring;

use Exception;
use Nxp\Core\Config\ConfigurationManager;
use Nxp\Core\Database\Factories\Table;

/**
 * SystemChecks class provides methods for checking and creating necessary tables in the database.
 *
 * @package Nxp\Core\Security\Monitoring
 * @return SystemChecks
 */
class SystemChecks
{

    protected $tableFactory;

    /**
     * SystemChecks constructor.
     *
     * @param TableFactory $tableFactory
     */
    public function __construct(Table $tableFactory)
    {
        $this->tableFactory = $tableFactory;
    }

    /**
     * Checks if the necessary tables exist in the database and creates them if they do not.
     *
     * @return void
     */
    public function checkTables()
    {
        $this->checkLogsTable();
        $this->checkCacheTable();
    }

    /**
     * Checks if the 'logs' table exists in the database and creates it if it does not.
     *
     * @return void
     */
    public function checkLogsTable()
    {
        $tableName = 'logs';
        $columns = [
            'level' => 'VARCHAR(255)',
            'message' => 'TEXT',
            'timestamp' => 'TIMESTAMP',
            'metadata' => 'JSON',
            'uuid' => 'CHAR(36)'
        ];

        if (!$this->tableFactory->tableExists($tableName)) {
            $this->tableFactory->createTable($tableName, $columns);
        }
    }

    /**
     * Checks if the 'cache' table exists in the database and creates it if it does not.
     *
     * @return void
     */
    public function checkCacheTable()
    {
        $databaseType = ConfigurationManager::get("database", "DATABASE_TYPE");
        $tableName = 'cache';


        if (!$this->tableFactory->tableExists($tableName)) {

            // Determine the appropriate column syntax based on the database system
            if ($databaseType === 'mysql') {
                $columns = [
                    '`key`' => 'VARCHAR(255)',
                    '`value`' => 'TEXT',
                    'expire' => 'VARCHAR(100)',
                    'uuid' => 'CHAR(36)'
                ];
            } elseif ($databaseType === 'pgsql' | $databaseType == "cockroachdb") {
                $columns = [
                    '"key"' => 'VARCHAR(255)',
                    '"value"' => 'TEXT',
                    'expire' => 'VARCHAR(100)',
                    'uuid' => 'CHAR(36)'
                ];
            } else {
                throw new Exception("Unknown Database Type (mysql, pgsql, cockroachdb are supported)");
            }

            // Create the table using the appropriate column syntax
            $this->tableFactory->createTable($tableName, $columns);
        }
    }
}
