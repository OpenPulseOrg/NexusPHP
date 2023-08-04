<?php

namespace Nxp\Core\Common\Abstracts\Middleware;

use Nxp\Core\Common\Interfaces\Middleware\MiddlewareInterface;

abstract class Middleware implements MiddlewareInterface
{
    // This method needs to be implemented by every concrete middleware class
    abstract public function handle($request, $next);
}
