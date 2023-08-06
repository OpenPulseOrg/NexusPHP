<?php

namespace Nxp\Core\Middleware;

use Nxp\Core\Common\Interfaces\Middleware\MiddlewareInterface;

/**
 * The MiddlewareChain class manages a chain of middleware and events to be executed during the request handling process.
 * Middleware are classes implementing the MiddlewareInterface, and they can perform tasks before and after handling the request.
 * Events are custom callbacks that can be executed before or after the middleware chain.
 */
class MiddlewareChain
{
    protected array $middlewareGroups = [];
    protected array $globalMiddleware = [];
    protected array $events = [];
    protected ?\Closure $errorHandler = null;
    protected array $stackTrace = [];

    /**
     * Add a middleware to the chain.
     *
     * @param MiddlewareInterface $middleware The middleware instance to be added.
     * @param int                 $priority   The priority of the middleware. Higher values indicate higher priority.
     * @param string              $group      Optional. The group name to which the middleware belongs. If null, the middleware is considered global.
     * @return void
     */
    public function addMiddleware(MiddlewareInterface $middleware, int $priority = 0, string $group = null): void
    {
        if ($group !== null) {
            $this->middlewareGroups[$group][] = ['middleware' => $middleware, 'priority' => $priority];
        } else {
            $this->globalMiddleware[] = ['middleware' => $middleware, 'priority' => $priority];
        }
    }

    /**
     * Add an event callback to the chain.
     *
     * @param string   $eventName The name of the event.
     * @param callable $callback  The callback function to be executed when the event is triggered.
     * @return void
     */
    public function addEvent(string $eventName, callable $callback): void
    {
        $this->events[$eventName][] = $callback;
    }

    /**
     * Set the global error handler for the middleware chain.
     *
     * @param callable $handler The callback function to be used as the error handler.
     * @return void
     */
    public function setErrorHandler(callable $handler): void
    {
        $this->errorHandler = $handler;
    }

    /**
     * Handle the request by executing the middleware chain and events.
     *
     * @param mixed $request The incoming request to be processed.
     * @param array $params  Optional. Additional parameters to be passed to the middleware.
     * @return mixed The final response after processing through the middleware chain.
     * @throws \Throwable If any error occurs during the request handling, it can be propagated or handled by the global error handler.
     */
    public function handle($request, array $params = []): mixed
    {
        $this->stackTrace = [];
        $this->executeEvents('before', $request);

        try {
            $response = $this->executeMiddleware($request, $params);
        } catch (\Throwable $e) {
            if ($this->errorHandler) {
                $response = call_user_func($this->errorHandler, $e);
            } else {
                throw $e; // No global error handler, rethrow the exception
            }
        }

        $this->executeEvents('after', $request);

        return $response;
    }

    /**
     * Execute the middleware chain.
     *
     * @param mixed $request The incoming request to be processed.
     * @param array $params  Optional. Additional parameters to be passed to the middleware.
     * @return mixed The final response after processing through the middleware chain.
     * @throws \Throwable If any error occurs during the request handling, it can be propagated or handled by the global error handler.
     */
    private function executeMiddleware($request, array $params = []): mixed
    {
        $this->stackTrace = [];
        $next = function ($req) {
            return $req;
        };

        // Execute global middleware first
        foreach ($this->globalMiddleware as $item) {
            $middleware = $item['middleware'];
            $this->stackTrace[] = get_class($middleware);
            $next = function ($req) use ($middleware, $next, $params) {
                try {
                    return $middleware->handle($req, $next, ...$params);
                } catch (\Throwable $e) {
                    throw $e; // Propagate exceptions to the global handler
                }
            };
        }

        // Execute middleware groups in priority order
        foreach ($this->middlewareGroups as $groupMiddleware) {
            usort($groupMiddleware, function ($a, $b) {
                return $a['priority'] <=> $b['priority'];
            });

            foreach ($groupMiddleware as $item) {
                $middleware = $item['middleware'];
                $this->stackTrace[] = get_class($middleware);
                $next = function ($req) use ($middleware, $next, $params) {
                    try {
                        return $middleware->handle($req, $next, ...$params);
                    } catch (\Throwable $e) {
                        throw $e; // Propagate exceptions to the global handler
                    }
                };
            }
        }

        // Start the middleware chain with the first middleware
        return $next($request);
    }

    /**
     * Execute the registered events of a specific type (before/after).
     *
     * @param string $eventName The name of the event type (before/after).
     * @param mixed  $request   The incoming request object to be processed by the events.
     * @return void
     */
    private function executeEvents(string $eventName, $request): void
    {
        if (isset($this->events[$eventName])) {
            foreach ($this->events[$eventName] as $event) {
                $event($request);
            }
        }
    }

    /**
     * Get the stack trace of executed middleware during the request handling process.
     *
     * @return array The array containing the names of executed middleware classes in the order they were executed.
     */
    public function getStackTrace(): array
    {
        return $this->stackTrace;
    }
}
