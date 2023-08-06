<?php

namespace Nxp\Core\Utils\Service;

use Exception;

class Container
{
    private static $instance = null;

    private $services = [];
    private $instances = [];
    private $tags = [];
    private $aliases = [];
    private $lazyServices = [];
    private $parameters = [];
    private $protected = [];
    private $middlewares = [];

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function setParameter(string $name, $value)
    {
        $this->parameters[$name] = $value;
    }

    public function getParameter(string $name)
    {
        if (!isset($this->parameters[$name])) {
            throw new Exception("Parameter not found: {$name}");
        }
        return $this->parameters[$name];
    }

    public function set(string $name, callable $resolver, bool $singleton = false, bool $protected = false)
    {
        if (isset($this->protected[$name])) {
            throw new Exception("Service {$name} is protected and cannot be overwritten.");
        }
        $this->services[$name] = [
            'resolver' => $resolver,
            'singleton' => $singleton,
        ];
        if ($protected) {
            $this->protected[$name] = true;
        }
    }

    public function alias(string $alias, string $service)
    {
        $this->aliases[$alias] = $service;
    }

    public function factory(string $name, callable $resolver)
    {
        $this->set($name, $resolver, false);
    }

    public function setLazy(string $name, callable $resolver, bool $singleton = false)
    {
        $this->lazyServices[$name] = [
            'resolver' => $resolver,
            'singleton' => $singleton,
        ];
    }

    public function has(string $name)
    {
        return isset($this->services[$name]) || isset($this->aliases[$name]) || isset($this->lazyServices[$name]);
    }

    public function clear(string $name)
    {
        unset($this->instances[$name]);
    }

    public function addMiddleware(callable $middleware)
    {
        $this->middlewares[] = $middleware;
    }

    private function resolve($name, $serviceData, $isLazy = false)
    {
        if ($serviceData['singleton'] && isset($this->instances[$name])) {
            return $this->instances[$name];
        }

        $service = $serviceData['resolver'];
        $resolvedService = $service($this);

        if ($serviceData['singleton']) {
            $this->instances[$name] = $resolvedService;
        }

        // Apply middlewares
        foreach ($this->middlewares as $middleware) {
            $resolvedService = $middleware($resolvedService, $name, $isLazy);
        }

        return $resolvedService;
    }

    public function get(string $name)
    {
        if (isset($this->aliases[$name])) {
            $name = $this->aliases[$name];
        }

        if (!isset($this->services[$name])) {
            throw new Exception("Service not found: {$name}");
        }

        return $this->resolve($name, $this->services[$name]);
    }



    public function addTag(string $tagName, string $serviceName)
    {
        $this->tags[$tagName][] = $serviceName;
    }

    public function getTagged(string $tagName)
    {
        $taggedServices = [];
        if (isset($this->tags[$tagName])) {
            foreach ($this->tags[$tagName] as $serviceName) {
                $taggedServices[] = $this->get($serviceName);
            }
        }
        return $taggedServices;
    }

   

    public function getLazy(string $name)
    {
        if (!isset($this->lazyServices[$name])) {
            throw new Exception("Lazy service not found: {$name}");
        }

        $service = $this->lazyServices[$name]['resolver'];

        if ($this->lazyServices[$name]['singleton'] && isset($this->instances[$name])) {
            return $this->instances[$name];
        }

        $resolvedService = $service($this);
        if ($this->lazyServices[$name]['singleton']) {
            $this->instances[$name] = $resolvedService;
        }

        return $resolvedService;
    }

    public function loadConfig(array $config)
    {
        foreach ($config as $name => $serviceConfig) {
            $resolver = $serviceConfig['resolver'] ?? null; // Use null coalescing to avoid undefined index
            $singleton = $serviceConfig['singleton'] ?? false;

            try {
                if ($resolver) { // Check if resolver is defined
                    $this->set($name, $resolver, $singleton);

                    if (isset($serviceConfig['tags'])) {
                        foreach ($serviceConfig['tags'] as $tagName) {
                            $this->addTag($tagName, $name);
                        }
                    }
                }
            } catch (Exception $e) {
                throw new Exception($e);
            }
        }
    }

    public function getAllServices()
    {
        // Combine both regular and lazy services
        $allServices = array_merge(
            array_keys($this->services),
            array_keys($this->lazyServices)
        );

        // Return or print the service names
        return $allServices;
    }
}
