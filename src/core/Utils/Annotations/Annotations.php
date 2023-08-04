<?php

namespace Nxp\Core\Utils\Annotations;

use ReflectionClass;

class Annotations
{
    private $reflection;
    private $annotations;

    public function __construct($class)
    {
        $this->reflection = new ReflectionClass($class);
        $this->annotations = [];
        $this->parseAnnotations();
    }

    private function parseAnnotations()
    {
        // Parse class annotations
        $this->annotations['class'] = $this->parseDocComment($this->reflection->getDocComment());

        // Parse properties annotations
        foreach ($this->reflection->getProperties() as $property) {
            $this->annotations['properties'][$property->getName()] = $this->parseDocComment($property->getDocComment());
        }

        // Parse methods annotations
        foreach ($this->reflection->getMethods() as $method) {
            $this->annotations['methods'][$method->getName()] = $this->parseDocComment($method->getDocComment());
        }
    }

    private function parseDocComment($docComment)
    {
        $annotations = [];
        if ($docComment) {
            $lines = explode("\n", $docComment);
            foreach ($lines as $line) {
                if (preg_match('/@Route\s*\(\s*\'(\w+)\'\s*,\s*\'(.*)\'\s*\)\s*\r?$/', $line, $matches)) {
                    $annotations['Route'][] = ['method' => $matches[1], 'path' => $matches[2]];
                }
            }
        }
        return $annotations;
    }


    public function getClassAnnotations()
    {
        return $this->annotations['class'] ?? [];
    }

    public function getMethodAnnotations($methodName)
    {
        return $this->annotations['methods'][$methodName] ?? [];
    }

    public function getPropertyAnnotations($propertyName)
    {
        return $this->annotations['properties'][$propertyName] ?? [];
    }

    public function getAllMethodAnnotations()
    {
        return $this->annotations['methods'] ?? [];
    }
}
