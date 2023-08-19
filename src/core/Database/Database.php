<?php
namespace Nxp\Core\Database;

use PDO;
use PDOException;
use Nxp\Core\Common\Patterns\Singleton;
use Nxp\Core\Config\ConfigurationManager;

class Database extends Singleton
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
    private static $instance = null;

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
            $databaseType = ConfigurationManager::get('database', 'DATABASE_TYPE');
            $host = ConfigurationManager::get('database', 'DATABASE_HOST');
            $port = ConfigurationManager::get('database', 'DATABASE_PORT');
            $dbname = ConfigurationManager::get('database', 'DATABASE_NAME');
            $username = ConfigurationManager::get('database', 'DATABASE_USER');
            $password = ConfigurationManager::get('database', 'DATABASE_PASS');

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