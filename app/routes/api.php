<?php

/**
 * API Routes
 * Defines API routes for handling HTTP requests in the PHP framework.
 * These routes are used when accessing the API of the framework.
 * All routes are automatically grouped based on your configureation settings in config/app.php.
 */

use Nxp\Core\Utils\Navigation\Router\Collection;

Collection::group('/api', [
    ["GET", "/", '\Nxp\Controllers\Welcome\ApiWelcomeController@index'],
    ['GET', '/hello', '\Nxp\Controllers\Welcome\ApiWelcomeController@hello'],
    ['GET', '/world', '\Nxp\Controllers\Welcome\ApiWelcomeController@world'],
]);