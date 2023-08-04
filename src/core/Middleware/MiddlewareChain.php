<?php

namespace Nxp\Core\Middleware;

use Nxp\Core\Common\Interfaces\Middleware\MiddlewareInterface;

class MiddlewareChain
{
    protected array $middlewareGroups = [];
    protected array $globalMiddleware = [];
    protected array $events = [];
    protected ?\Closure $errorHandler = null;
    protected array $stackTrace = [];

    public function addMiddleware(MiddlewareInterface $middleware, int $priority = 0, string $group = null): void
    {
        if ($group !== null) {
            $this->middlewareGroups[$group][] = ['middleware' => $middleware, 'priority' => $priority];
        } else {
            $this->globalMiddleware[] = ['middleware' => $middleware, 'priority' => $priority];
        }
    }

    public function addEvent(string $eventName, callable $callback): void
    {
        $this->events[$eventName][] = $callback;
    }

    public function setErrorHandler(callable $handler): void
    {
        $this->errorHandler = $handler;
    }

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

    private function executeMiddleware($request, array $params = []): mixed
    {
        $this->stackTrace = [];
        $next = function ($req) {
            return $req;
        };

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

        return $next($request);
    }

    private function executeEvents(string $eventName, $request): void
    {
        if (isset($this->events[$eventName])) {
            foreach ($this->events[$eventName] as $event) {
                $event($request);
            }
        }
    }

    public function getStackTrace(): array
    {
        return $this->stackTrace;
    }
}
