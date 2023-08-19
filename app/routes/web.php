<?php


/**
 * Web Routes
 * Defines web routes for handling HTTP requests in the PHP framework.
 * These routes are the routes that you user will visit if they are accessing it via a web browser
 */

use Nxp\Core\Utils\Navigation\Router\Collection;

// Home Routes
Collection::add("GET", "/", "\Nxp\Controllers\Welcome\WelcomeController@welcome");
Collection::add("POST", "/", "\Nxp\Controllers\Welcome\WelcomeController@welcome");


Collection::add("GET", "/cats", "\Nxp\Controllers\Welcome\WelcomeController@cats");

Collection::add("GET", "/test/:id", "\Nxp\Controllers\Welcome\WelcomeController@test");
Collection::add('GET', '/test/:id/edit', '\Nxp\Controllers\Welcome\WelcomeController@testedit');


// Auth Routes
Collection::addOrGroup('GET', '/login', '\Nxp\Controllers\Auth\AuthController@login', '/auth');
Collection::addOrGroup('GET', '/register', '\Nxp\Controllers\Auth\AuthController@register', '/auth');
