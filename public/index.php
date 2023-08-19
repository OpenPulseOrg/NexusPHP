<?php

use Nxp\Core\Bootstrap\Bootstrap;

/**
 * This file should remain empty. Routes should be defined in the app/routes folder.
 * You have two choices for defining routes:
 * 
 * web.php
 * web.php is used to define graphic web routes for accessing your website through a browser.
 * 
 * api.php
 * api.php is used to define API routes for accessing your framework through an API.
 */

// Constants
const VENDOR_PATH = __DIR__ . "/../vendor/autoload.php";
const CONFIG_PATH = __DIR__ . "/../app/config/";
const DOCUMENTATION_URL = "https://docs.nexusphp.io";
const REQUIRED_PHP_VERSION = '8.0';
const MISSING_VENDOR_WARNING = "Warning: The 'vendor' folder is missing. Please run 'composer install' or 'composer update' to install dependencies. Visit our documentation on how to do this: " . DOCUMENTATION_URL;

try {
    // Check PHP version
    if (version_compare(PHP_VERSION, REQUIRED_PHP_VERSION, '<')) {
        throw new Exception("Required PHP version is " . REQUIRED_PHP_VERSION . " or higher.");
    }

    // Check and handle missing vendor folder
    if (!file_exists(VENDOR_PATH)) {
        if (ini_get('display_errors') != 1) {
            ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);
            error_reporting(E_ALL);
        }

        throw new Exception(MISSING_VENDOR_WARNING);
    }

    // Check required PHP extensions
    $requiredExtensions = ['mbstring', 'curl', 'pdo', 'pdo_mysql', 'pdo_pgsql'];
    foreach ($requiredExtensions as $extension) {
        if (!extension_loaded($extension)) {
            throw new Exception("Required PHP extension '$extension' is missing.");
        }
    }

    // Check and rename required config files
    $requiredFiles = ['app', 'database', 'keys', "constants"];
    foreach ($requiredFiles as $fileName) {
        $filePath = CONFIG_PATH . $fileName . '.php';
        $exampleFilePath = CONFIG_PATH . $fileName . '.example.php';

        if (!file_exists($filePath)) {
            if (file_exists($exampleFilePath)) {
                rename($exampleFilePath, $filePath);
            } else {
                throw new Exception("Required config file '$fileName.php' is missing. Rename '$fileName.example.php' to '$fileName.php'.");
            }
        }
    }

    // Use Composer autoloader
    require_once VENDOR_PATH;

    // Init the Bootstrap. 
    Bootstrap::init();
} catch (Exception $e) {
    // Handle exceptions gracefully
    echo '<div style="font-family: Arial, sans-serif; background-color: #f2f2f2; padding: 20px; border: 1px solid #ccc;">';
    echo '<h1>An Error Occurred</h1>';
    echo '<p>' . $e->getMessage() . '</p>';
    echo '</div>';
}
