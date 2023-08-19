<?php

namespace Nxp\Core\Common\Abstracts\Middleware;

use Nxp\Core\Common\Interfaces\Middleware\MiddlewareInterface;

/**
 * Abstract class Middleware
 *
 * This class serves as the base for creating middleware classes. Middleware in a PHP application
 * can intercept and process incoming requests before they reach the actual request handler (e.g., controller).
 * Concrete middleware classes must extend this abstract class and implement the 'handle' method.
 *
 * @package Nxp\Core\Common\Abstracts\Middleware
 */
abstract class Middleware implements MiddlewareInterface
{
    /**
     * Process the incoming request and optionally pass it to the next middleware in the chain.
     *
     * This method needs to be implemented by every concrete middleware class.
     *
     * @param mixed $request The incoming request object or data that needs to be processed.
     * @param \Closure $next The next middleware in the chain. The request can be passed to this closure for further processing.
     * @return mixed The response returned by this middleware's processing or the subsequent middleware in the chain.
     * @throws \Exception If an error occurs during the processing of the request.
     */
    abstract public function handle($request, $next);
}
