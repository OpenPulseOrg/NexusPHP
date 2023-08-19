<?php

namespace Nxp\Core\Utils\HTTP;

/**
 * Request class for handling HTTP requests.
 *
 * @package Nxp\Core\Utils\HTTP
 */
class Request
{
    private $method;
    private $uri;
    private $headers;
    private $parameters;

    /**
     * Request constructor.
     *
     * Initializes the Request object by populating the method, URI, headers, and parameters from the global variables.
     */
    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->uri = $_SERVER['REQUEST_URI'];
        $this->headers = getallheaders();
        $this->parameters = $_REQUEST;
    
        // If the request content type is JSON, decode the input
        if (isset($this->headers['Content-Type']) && $this->headers['Content-Type'] === 'application/json') {
            $this->parameters = array_merge($this->parameters, json_decode(file_get_contents('php://input'), true));
        }
    }

    /**
     * Get the HTTP method used for the request.
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Get the URI (Uniform Resource Identifier) of the request.
     *
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * Get all the headers of the request.
     *
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Get the value of a specific header by its name.
     *
     * @param string $name The name of the header.
     * @return string|null The value of the header if found, or null if not found.
     */
    public function getHeader($name)
    {
        return $this->headers[$name] ?? null;
    }

    /**
     * Get all the parameters of the request.
     *
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * Get the value of a specific parameter by its name.
     *
     * @param string $name The name of the parameter.
     * @return mixed|null The value of the parameter if found, or null if not found.
     */
    public function getParameter($name)
    {
        return $this->parameters[$name] ?? null;
    }
}
