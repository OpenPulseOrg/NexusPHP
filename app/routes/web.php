<?php


/**
 * Web Routes
 * Defines web routes for handling HTTP requests in the PHP framework.
 * These routes are the routes that you user will visit if they are accessing it via a web browser
 */

use Nxp\Core\Utils\Navigation\Router\RouteCollection;
use Nxp\Middlewares\testMiddleware;

// Home Routes
RouteCollection::add("GET", "/", "\Nxp\Controllers\Welcome\WelcomeController@welcome");

RouteCollection::add("GET", "/test/:id", "\Nxp\Controllers\Welcome\WelcomeController@test");
RouteCollection::add('GET', '/test/:id/edit', '\Nxp\Controllers\Welcome\WelcomeController@testedit');


// Auth Routes
RouteCollection::addOrGroup('GET', '/login', '\Nxp\Controllers\Auth\AuthController@login', '/auth');
RouteCollection::addOrGroup('GET', '/register', '\Nxp\Controllers\Auth\AuthController@register', '/auth');
