<?php

namespace Nxp\Core\Utils\Service;

use Exception;

class Container
{
    private static $instance = null;

    private $services = [];
    private $instances = [];
    private $tags = [];
    private $lazyServices = [];

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function set(string $name, callable $resolver, bool $singleton = false)
    {
        $this->services[$name] = [
            'resolver' => $resolver,
            'singleton' => $singleton,
        ];
    }

    public function get(string $name)
    {
        if (!isset($this->services[$name])) {
            throw new Exception("Service not found: {$name}");
        }

        $service = $this->services[$name]['resolver'];

        if ($this->services[$name]['singleton'] && isset($this->instances[$name])) {
            return $this->instances[$name];
        }

        $resolvedService = $service($this);
        if ($this->services[$name]['singleton']) {
            $this->instances[$name] = $resolvedService;
        }

        return $resolvedService;
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

    public function setLazy(string $name, callable $resolver, bool $singleton = false)
    {
        $this->lazyServices[$name] = [
            'resolver' => $resolver,
            'singleton' => $singleton,
        ];
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
