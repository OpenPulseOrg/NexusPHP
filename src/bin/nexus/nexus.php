<?php

use Nxp\Core\Config\ConfigHandler;
use Nxp\Core\Database\Database;

require_once __DIR__ . '/../../../vendor/autoload.php';

// Register the autoloader
spl_autoload_register(function ($class) {
    $migrationDirectory = __DIR__ . '/../../../migrations/';
    $migrationFiles = glob($migrationDirectory . '*.php');

    // Load class files from migrations directory
    foreach ($migrationFiles as $file) {
        // Extract class name from the file name by removing date prefix and .php extension
        $fileName = basename($file, '.php');
        $fileNameClass = substr($fileName, strpos($fileName, '_') + 1);

        if ($fileNameClass === $class) {
            require_once $file;
            return;
        }
    }

    $srcDirectory = __DIR__ . '/src/';
    $classFile = str_replace('\\', '/', $class) . '.php';
    $classPath = $srcDirectory . $classFile;

    // Load class files from src directory
    if (file_exists($classPath)) {
        require_once $classPath;
        return;
    }
});

// Get the command-line argument
$command = isset($argv[1]) ? $argv[1] : '';

// Establish the database connection
$db = Database::getInstance()->connect();;

// Define the commands and their corresponding classes and methods
$commands = [
    'help' => [
        'class' => '\Info\Help',
        'method' => 'displayHelp',
        'description' => 'Show help information'
    ],
    'migration' => [
        'generate' => [
            'class' => '\Database\Migration',
            'method' => 'generate',
            'description' => 'Generate a new migration file'
        ],
        'migrate' => [
            'class' => '\Database\Migration',
            'method' => 'migrate',
            'description' => 'Run database migrations'
        ],
        'convert' => [
            'class' => '\Database\Migration',
            'method' => 'convertSqlToMigrationFile',
            'description' => 'Converts .sql file to both a pgSQL and MySQL migration files'
        ],
        'rollback' => [
            'class' => '\Database\Migration',
            'method' => 'rollback',
            'description' => 'Rollback the last executed migration'
        ],
    ],
    'controller' => [
        'generate' => [
            'class' => '\Controller\controllerGenerator',
            'method' => 'generate',
            'description' => 'Generate a new controller file'
        ]
    ],
    'download' => [
        'class' => "\Download\DownloadManager",
        'method' => "download",
        'description' => "Downloads a package"
    ]
];


// Get the command-line arguments
$command = isset($argv[1]) ? $argv[1] : '';
$commandSub = isset($argv[2]) ? $argv[2] : '';
$commandArgument = isset($argv[3]) ? $argv[3] : '';

$questionHelper = new QuestionHelper();

// Handle the 'help' command
if ($command === 'help') {
    (new \Info\Help)->displayHelp($commands);
} elseif (isset($commands[$command])) {
    if (is_array($commands[$command]) && array_key_exists('class', $commands[$command]) && array_key_exists('method', $commands[$command])) {
        // Handle commands without subcommands
        $class = $commands[$command]['class'];
        $method = $commands[$command]['method'];
        executeCommand($class, $method, $questionHelper);
    } elseif (isset($commands[$command][$commandSub])) {
        // Handle commands with subcommands
        $class = $commands[$command][$commandSub]['class'];
        $method = $commands[$command][$commandSub]['method'];
        executeCommand($class, $method, $questionHelper);
    } else {
        $questionHelper->output("Invalid subcommand for $command. Please use one of the following: " . implode(', ', array_keys($commands[$command])) . "\n", "red");
    }
} else {
    $questionHelper->output("Invalid command. Please use one of the following: " . implode(', ', array_keys($commands)) . "\n", "red");
}

function executeCommand($class, $method, $questionHelper)
{
    if (class_exists($class)) {
        $instance = new $class();

        if (method_exists($instance, $method)) {
            $instance->{$method}();
        } else {
            $questionHelper->output("Invalid method '$method' for class '$class'.\n", "red");
        }
    } else {
        $questionHelper->output("Class '$class' does not exist.\n", "red");
    }
}
