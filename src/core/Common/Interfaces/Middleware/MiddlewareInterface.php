<?php

namespace Nxp\Core\Common\Interfaces\Middleware;

interface MiddlewareInterface
{
    public function handle($request, $next);
}
