<?php

namespace Nxp\Core\Utils\HTTP;

/**
 * Request class for handling HTTP responses.
 *
 * @package Nxp\Core\Utils\HTTP
 */
class Response
{
    private $statusCode;
    private $headers;
    private $body;

    /**
     * Initializes a new instance of the Response class.
     *
     * @param int    $statusCode The HTTP status code. Defaults to 200.
     * @param array  $headers    An associative array of HTTP headers. Defaults to an empty array.
     * @param string $body       The body of the response. Defaults to an empty string.
     */
    public function __construct($statusCode = 200, $headers = [], $body = '')
    {
        $this->statusCode = $statusCode;
        $this->headers = $headers;
        $this->body = $body;
    }

    /**
     * Sets the HTTP status code of the response.
     *
     * @param int $statusCode The HTTP status code.
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
    }

    /**
     * Gets the HTTP status code of the response.
     *
     * @return int The HTTP status code.
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Sets an HTTP header for the response.
     *
     * @param string $name  The name of the header.
     * @param string $value The value of the header.
     */
    public function setHeader($name, $value)
    {
        $this->headers[$name] = $value;
    }

    /**
     * Gets the HTTP headers of the response.
     *
     * @return array An associative array of HTTP headers.
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Sets the body of the response.
     *
     * @param string $body The body of the response.
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * Gets the body of the response.
     *
     * @return string The body of the response.
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Sends the HTTP response.
     */
    public function send()
    {
        // Set the status code
        http_response_code($this->statusCode);

        // Set the headers
        foreach ($this->headers as $name => $value) {
            header("$name: $value");
        }

        // Send the response body
        echo $this->body;
    }

    public function json($data)
    {
        $this->setHeader('Content-Type', 'application/json');
        $this->setBody(json_encode($data));
    }
}
