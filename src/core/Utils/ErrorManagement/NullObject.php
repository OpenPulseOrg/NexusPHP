<?php

namespace Nxp\Core\Utils\ErrorManagement;

/**
 * Null object for handling calls to undefined methods and access to undefined properties.
 *
 * @package Nxp\Core\Utils\ErrorManagement
 */
class NullObject
{
    /**
     * Handles the calls to undefined methods.
     *
     * This method is called when a method is invoked on the NullObject instance that doesn't exist.
     * It simply returns null.
     *
     * @param string $method    The name of the method being called.
     * @param array  $arguments The arguments passed to the method.
     * @return null
     */
    public function __call($method, $arguments)
    {
        return null;
    }

    /**
     * Handles the access to undefined properties.
     *
     * This method is called when a property of the NullObject instance is accessed that doesn't exist.
     * It simply returns null.
     *
     * @param string $property The name of the property being accessed.
     * @return null
     */
    public function __get($property)
    {
        return null;
    }
}
