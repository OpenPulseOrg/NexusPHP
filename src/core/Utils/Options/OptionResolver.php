<?php

namespace Nxp\Core\Utils\Options;

class OptionResolver
{
    private $defaults = [];
    private $allowedTypes = [];
    private $mandatory = [];
    private $normalizers = [];
    private $allowedValues = [];

    public function setDefaults(array $defaults)
    {
        $this->defaults = $defaults;
    }

    public function setAllowedTypes(string $option, string $type)
    {
        $this->allowedTypes[$option] = $type;
    }

    public function setMandatory(array $options)
    {
        $this->mandatory = $options;
    }

    public function setNormalizer(string $option, callable $normalizer)
    {
        $this->normalizers[$option] = $normalizer;
    }

    public function setAllowedValues(string $option, array $values)
    {
        $this->allowedValues[$option] = $values;
    }

    public function resolve(array $options)
    {
        $resolved = array_merge($this->defaults, $options);
        $this->validateOption($resolved);
        return $resolved;
    }

    private function validateOption(array &$options, $path = '')
    {
        foreach ($options as $key => &$value) {
            $currentPath = $path ? $path . '.' . $key : $key;

            // Validate mandatory options
            if (in_array($currentPath, $this->mandatory, true) && !isset($options[$key])) {
                throw new \InvalidArgumentException(sprintf('Option "%s" is mandatory.', $currentPath));
            }

            // Validate option types
            if (isset($this->allowedTypes[$currentPath]) && gettype($value) !== $this->allowedTypes[$currentPath]) {
                throw new \InvalidArgumentException(sprintf('Option "%s" must be of type %s.', $currentPath, $this->allowedTypes[$currentPath]));
            }

            // Normalize options
            if (isset($this->normalizers[$currentPath])) {
                $value = ($this->normalizers[$currentPath])($value);
            }

            // Validate allowed values
            if (isset($this->allowedValues[$currentPath]) && !in_array($value, $this->allowedValues[$currentPath], true)) {
                throw new \InvalidArgumentException(sprintf('Option "%s" must be one of the allowed values.', $currentPath));
            }

            // Recursively check nested options
            if (is_array($value)) {
                $this->validateOption($value, $currentPath);
            }
        }
    }
}
