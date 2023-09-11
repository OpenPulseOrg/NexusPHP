<?php

namespace Nxp\Core\Utils\Controller;

/**
 * Class for handling controller method execution.
 *
 * @package Nxp\Core\Utils\Controller
 */
class ControllerHandler
{
    protected $controller;
    protected $method;
    protected $parameters;

    /**
     * ControllerHandler constructor.
     *
     * @param mixed $controller The controller class name or an array with [controllerClass, method].
     * @param string $method The method name to be called on the controller.
     * @param array $parameters The parameters to be passed to the controller method.
     */
    public function __construct($controller, $method, $parameters = [])
    {
        $this->controller = $controller;
        $this->method = $method;
        $this->parameters = $parameters;
    }

    /**
     * Handles the controller method execution.
     */
    public function handle()
    {
        // Check if the controller class exists
        if (is_array($this->controller)) {
            [$controllerClass, $method] = $this->controller;
        } else {
            $controllerClass = $this->controller;
            $method = $this->method;
        }

        if (class_exists($controllerClass)) {
            $controllerInstance = new $controllerClass();

            // Check if the controller method exists
            if (method_exists($controllerInstance, $method)) {
                // Call the controller method with parameters
                call_user_func_array([$controllerInstance, $method], $this->parameters);
            } else {
                // Method not found in the controller
                echo "Error: Method {$method} does not exist in the {$controllerClass} class.";
            }
        } else {
            // Controller class not found
            echo "Error: Controller class {$controllerClass} does not exist.";
        }
    }
}
