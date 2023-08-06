<?php
namespace Nxp\Core\Database;

use Nxp\Core\Config\ConfigHandler;
use PDO;
use PDOException;

class Database
{
    /**
     * @var PDO|null The PDO instance used for the database connection.
     */
    private static $pdo = null;

    /**
     * @var int Number of connection attempts made.
     */
    private static $connectionAttempts = 0;

    /**
     * @var Database|null The singleton instance of the Database class.
     */
    public static $instance = null;

    /**
     * Private constructor to prevent direct object creation.
     */
    private function __construct(){}

    /**
     * Get the singleton instance of the Database class.
     *
     * @return Database The instance of the Database class.
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Establishes a database connection using PDO.
     *
     * @return PDO The PDO instance for the established database connection.
     * @throws PDOException If there is an error during the database connection process.
     */
    public static function connect()
    {
        // Check if the PDO extension is available
        if (!extension_loaded('pdo')) {
            throw new PDOException('PDO extension is not available.');
        }

        // Check for required PDO extensions
        $requiredExtensions = ['pdo_mysql', 'pdo_pgsql'];
        $missingExtensions = array_diff($requiredExtensions, get_loaded_extensions());
        if (!empty($missingExtensions)) {
            throw new PDOException('Required PDO extensions are not available: ' . implode(', ', $missingExtensions));
        }

        if (self::$pdo === null) {
            // Get database connection parameters from configuration
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

                // Create the PDO instance and establish the database connection
                self::$pdo = new PDO($dsn, $username, $password, $options);
            } catch (PDOException $e) {
                // If a connection error occurs, retry up to three times
                if (self::$connectionAttempts < 3) {
                    self::$connectionAttempts++;
                    return self::connect();
                }
                // If all attempts fail, throw the PDOException
                throw new PDOException($e->getMessage(), (int)$e->getCode());
            }
        }

        // Return the PDO instance for the database connection
        return self::$pdo;
    }

    /**
     * Disconnects from the database by resetting the PDO instance and connection attempts counter.
     */
    public static function disconnect()
    {
        self::$pdo = null;
        self::$connectionAttempts = 0;
    }
}
