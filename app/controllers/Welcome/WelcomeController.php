<?php

namespace Nxp\Controllers\Welcome;

use Nxp\Core\Utils\HTTP\Request;
use Nxp\Core\Utils\HTTP\Response;
use Nxp\Core\Config\ConfigHandler;
use Nxp\Core\Utils\Assets\AssetLoader;
use Nxp\Core\Templating\TemplateEngine;
use Nxp\Core\Utils\API\APIRequestHandler;

class WelcomeController
{
    public function welcome()
    {
        $templateEngine = new TemplateEngine(__DIR__ . "/../../views/Welcome");
        echo $templateEngine->render("Welcome", [
            "title" => ConfigHandler::get("app", "CORE_TITLE"),
            'cssPath' => AssetLoader::loadCSS("welcome.css"),
            'faviconPath' => AssetLoader::generateFavicon("favicon.png"),
            'jsPath' => AssetLoader::loadJS("app.js"),
        ]);

    }

    public function cats()
    {
        // Instantiate the APIRequestHandler object
        $apiHandler = new APIRequestHandler('https://catfact.ninja/', '');

        // Create a new instance of the Request class
        $request = new Request();

        // Send a GET request to the "fact" endpoint using the Request object
        $endpoint = 'fact';
        $response = $apiHandler->sendRequest($request, $endpoint);

        // Handle the response or error
        if ($response instanceof Response) {
            // Success! Process the response data
            $responseBody = $response->getBody();

            $data = json_decode($responseBody, true);

            // Extract the fact from the decoded data
            $fact = $data['fact'];

            // Display the fact
            echo $fact;
        } else {
            // Handle the error
            echo $response;
        }
    }

}
