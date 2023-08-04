<?php

namespace Nxp\Middlewares;

use Nxp\Core\Common\Interfaces\Middleware\MiddlewareInterface;

class testMiddleware implements MiddlewareInterface
{
    public function handle($request, $next)
    {
        // Do something within this middleware
        // This return is required as it will either call the next middleware 
        // that is in series or it will call the final and finishing executing.
        return $next($request);
    }
}
