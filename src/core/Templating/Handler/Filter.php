<?php

namespace Nxp\Core\Templating\Handler;

class Filter
{
    private $filters = [];

    public function register($name, $callable)
    {
        if (!is_callable($callable)) {
            throw new \InvalidArgumentException("The provided filter must be callable.");
        }

        $this->filters[$name] = $callable;
    }

    public function apply($name, $value, $args = [])
    {
        if (!isset($this->filters[$name])) {
            throw new \RuntimeException("Filter '{$name}' is not registered.");
        }

        return call_user_func_array($this->filters[$name], array_merge([$value], $args));
    }
}
