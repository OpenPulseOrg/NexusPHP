<?php

namespace Nxp\Controllers\Welcome;

use Nxp\Core\Utils\HTTP\Request;
use Nxp\Core\Utils\HTTP\Response;

class ApiWelcomeController
{
    public function hello(Request $request, Response $response)
    {
        $json = [
            "name" => "kevingorman1000",
            "uuid" => 2
        ];

        $response->json($json);
        $response->send();
    }

    public function world(Request $request, Response $response)
    {
        $response->json(["message" => "Hello from /api/world!"]);
        $response->send();
    }

    public function index(Request $request, Response $response)
    {
        $data = [
            "message" => "Welcome to the API",
            "version" => "1.0.0",
            "documentation_url" => "https://opencad.io/api/docs",
        ];

        $response->json($data);
        $response->send();
    }
}
