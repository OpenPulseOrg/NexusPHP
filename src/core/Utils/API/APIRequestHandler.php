<?php

namespace Nxp\Core\Utils\API;

use Nxp\Core\Utils\HTTP\Request;
use Nxp\Core\Utils\HTTP\Response;

/**
 * APIRequestHandler class for sending API requests.
 *
 * @package Nxp\Core\Utils\API
 */
class APIRequestHandler
{
    private $apiUrl;
    private $apiKey;
    private $timeout;
    private $sslVerify;

    /**
     * APIRequestHandler constructor.
     *
     * @param string $apiUrl     The base URL of the API.
     * @param string $apiKey     The API key used for authentication.
     * @param int    $timeout    The request timeout in seconds. Default is 30 seconds.
     * @param bool   $sslVerify  Whether to verify SSL certificates for HTTPS requests. Default is true.
     */
    public function __construct($apiUrl, $apiKey, $timeout = 30, $sslVerify = true)
    {
        $this->apiUrl = $apiUrl;
        $this->apiKey = $apiKey;
        $this->timeout = $timeout;
        $this->sslVerify = $sslVerify;
    }

    /**
     * Sends an API request.
     *
     * @param Request $request The HTTP request object.
     * @param string  $endpoint The API endpoint or path.
     *
     * @return Response The response from the API.
     */
    public function sendRequest(Request $request, $endpoint)
    {
        $url = $this->apiUrl . $endpoint;

        $ch = curl_init();

        $defaultHeaders = array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->apiKey
        );

        $headers = array_merge($defaultHeaders, $request->getHeaders());

        $curlOptions = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_SSL_VERIFYPEER => $this->sslVerify,
        );

        $method = $request->getMethod();
        $data = $request->getParameters();

        // Set the request method
        $method = strtoupper($method);
        if ($method === 'POST') {
            $curlOptions[CURLOPT_POST] = true;
            if (!empty($data)) {
                // Set the request data
                $curlOptions[CURLOPT_POSTFIELDS] = $this->prepareRequestData($data);
            }
        } elseif ($method === 'PUT') {
            $curlOptions[CURLOPT_CUSTOMREQUEST] = 'PUT';
            if (!empty($data)) {
                $curlOptions[CURLOPT_POSTFIELDS] = $this->prepareRequestData($data);
            }
        } elseif ($method === 'DELETE') {
            $curlOptions[CURLOPT_CUSTOMREQUEST] = 'DELETE';
        }

        curl_setopt_array($ch, $curlOptions);

        $responseBody = curl_exec($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        $response = new Response($statusCode);
        $response->setBody($responseBody);

        return $response;
    }

    /**
     * Prepares the request data based on its format.
     *
     * @param mixed $data The request data to prepare.
     *
     * @return mixed The prepared request data.
     */
    private function prepareRequestData($data)
    {
        if (is_array($data)) {
            // Check if data is in JSON format or form data format
            $isJson = (count($data) > 0 && is_string(key($data)));

            if ($isJson) {
                return json_encode($data);
            } else {
                return http_build_query($data);
            }
        }

        return $data;
    }
}
