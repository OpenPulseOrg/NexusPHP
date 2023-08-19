<?php

namespace Nxp\Core\Utils\Navigation\Router;

use Exception;

class Loader
{
    // Set a maximum file size for route files (e.g., 1MB)
    const MAX_FILE_SIZE = 1048576;

    public static function loadFromFile($file)
    {
        // File Extension Check
        if (pathinfo($file, PATHINFO_EXTENSION) !== 'php') {
            throw new Exception("Invalid file extension. Only .php files are allowed.");
        }

        // File Existence Check
        if (!file_exists($file)) {
            throw new Exception("Router file does not exist.");
        }

        // File Size Limit Check
        if (filesize($file) > self::MAX_FILE_SIZE) {
            throw new Exception("Router file size exceeds the maximum allowed limit.");
        }

        // File Content Check (basic check for route related terms)
        $content = file_get_contents($file);
        $pattern = '/Collection::\w+\(/';
        if (!preg_match($pattern, $content)) {
            throw new Exception("The provided file does not seem to contain valid route definitions.");
        }

        require $file;
    }

    /**
     * @deprecated This function is deprecated and will be removed in future versions.
     */
    public static function load($file)
    {
        trigger_error('load() is deprecated and will be removed in future versions.', E_USER_DEPRECATED);
        self::loadFromFile($file);
    }
}
