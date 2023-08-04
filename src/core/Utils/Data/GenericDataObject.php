<?php

namespace Nxp\Core\Utils\Data;

/**
 * Generic data object for storing and manipulating data.
 *
 * @package Nxp\Core\Utils\Data
 */
class GenericDataObject
{
    protected $properties = [];

    /**
     * Constructs a new GenericDataObject instance.
     *
     * @param array $data The data to populate the object with.
     * 
     * @return void
     * 
     * @throws InvalidArgumentException if the data is invalid.
     */
    public function __construct(array $data)
    {
        $this->validateData($data);

        foreach ($data as $key => $value) {
            $this->properties[$key] = $value;
        }
    }

    /**
     * Gets a property value by name.
     *
     * @param string $name The name of the property to get.
     *
     * @return mixed The value of the property or null if it doesn't exist.
     */
    public function __get($name)
    {
        if (array_key_exists($name, $this->properties)) {
            return $this->properties[$name];
        }

        return null;
    }

    /**
     * Sets a property value by name.
     *
     * @param string $name The name of the property to set.
     * @param mixed $value The value to set.
     *
     * @return void
     */
    public function __set($name, $value)
    {
        $this->properties[$name] = $value;
    }

    /**
     * Checks if a property exists by name.
     *
     * @param string $name The name of the property to check.
     *
     * @return bool True if the property exists, false otherwise.
     */
    public function __isset($name)
    {
        return isset($this->properties[$name]);
    }

    /**
     * Converts the object to a JSON string.
     *
     * @return string The JSON representation of the object.
     */
    public function toJson()
    {
        return json_encode($this->properties);
    }

    /**
     * Populates the object with data from a JSON string.
     *
     * @param string $json The JSON string to populate the object with.
     *
     * @return void
     * 
     * @throws InvalidArgumentException if the JSON is invalid.
     */
    public function fromJson($json)
    {
        $data = json_decode($json, true);

        if (!is_array($data)) {
            throw new \InvalidArgumentException('Invalid JSON');
        }

        $this->validateData($data);

        $this->properties = $data;
    }

    /**
     * Validates the data used to populate the object.
     *
     * @param array $data The data to validate.
     *
     * @return void
     * 
     * @throws InvalidArgumentException if the data is invalid.
     */
    protected function validateData(array $data)
    {
        // Perform validation logic here
    }
}
