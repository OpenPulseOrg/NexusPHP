<?php

namespace Nxp\Core\Common\Interfaces\Middleware;

/**
 * The MiddlewareInterface defines the contract for middleware classes that can intercept and process HTTP requests.
 *
 * Middleware acts as a filter or a pre/post-processor for HTTP requests, allowing you to perform actions before
 * a request reaches its final destination (e.g., controller) or after the response has been generated.
 */
interface MiddlewareInterface
{
    /**
     * Handle an incoming HTTP request and optionally pass it to the next middleware in the chain.
     *
     * This method is responsible for processing the incoming HTTP request and deciding whether to proceed
     * with the request to the next middleware or stop the processing and return a response.
     *
     * @param mixed $request The incoming HTTP request object or data representing the request.
     * @param callable $next The next middleware in the chain to be called if processing should continue.
     *
     * @return mixed Returns either an HTTP response or the result of the next middleware in the chain.
     *               If this middleware decides to stop the request processing and return a response.
     *               If it decides to pass the request to the next middleware, it should call the $next
     *               callable with the request and return the result returned by the $next callable.
     *
     * @throws \Throwable If an error occurs during request processing, this method can throw an exception.
     *                    The exception will be caught by the application's error handling mechanism.
     */
    public function handle($request, $next);
}
