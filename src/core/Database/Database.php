<?php
namespace Nxp\Core\Database;

use Nxp\Core\Config\ConfigHandler;
use PDO;
use PDOException;

class Database
{
    private static $pdo = null;
    private static $connectionAttempts = 0;
    public static $instance = null;

    private function __construct(){}

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }


    public static function connect()
    {
        if (!extension_loaded('pdo')) {
            throw new PDOException('PDO extension is not available.');
        }

        $requiredExtensions = ['pdo_mysql', 'pdo_pgsql'];
        $missingExtensions = array_diff($requiredExtensions, get_loaded_extensions());

        if (!empty($missingExtensions)) {
            throw new PDOException('Required PDO extensions are not available: ' . implode(', ', $missingExtensions));
        }

        if (self::$pdo === null) {
            $databaseType = ConfigHandler::get('database', 'DATABASE_TYPE');
            $host = ConfigHandler::get('database', 'DATABASE_HOST');
            $port = ConfigHandler::get('database', 'DATABASE_PORT');
            $dbname = ConfigHandler::get('database', 'DATABASE_NAME');
            $username = ConfigHandler::get('database', 'DATABASE_USER');
            $password = ConfigHandler::get('database', 'DATABASE_PASS');

            try {
                if ($databaseType === 'mysql') {
                    // MySQL connection
                    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
                    $options = [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false,
                    ];
                } elseif ($databaseType === 'pgsql') {
                    // PostgreSQL connection
                    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;user=$username;password=$password;sslmode=require";
                    $options = [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_PERSISTENT => true,
                    ];
                } elseif ($databaseType === 'cockroachdb') {
                    // CockroachDB connection with cluster identifier
                    $clusterIdentifier = ConfigHandler::get('database', 'COCKROACHDB_CLUSTER_IDENTIFIER');
                    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;user=$username;password=$password;sslmode=require;sslrootcert=" . __DIR__ . '/../../storage/certs/cert3.pem';
                    $options = [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    ];
                } else {
                    throw new PDOException("Invalid database type: $databaseType");
                }

                self::$pdo = new PDO($dsn, $username, $password, $options);
            } catch (PDOException $e) {
                if (self::$connectionAttempts < 3) {
                    self::$connectionAttempts++;
                    return self::connect();
                }

                throw new PDOException($e->getMessage(), (int)$e->getCode());
            }
        }

        return self::$pdo;
    }

    public static function disconnect()
    {
        self::$pdo = null;
        self::$connectionAttempts = 0;
    }
}
