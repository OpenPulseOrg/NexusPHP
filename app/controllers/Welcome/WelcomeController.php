<?php

namespace Nxp\Controllers\Welcome;

use Nxp\Core\Templating\TemplateEngine;
use Nxp\Core\Utils\HTTP\Request;
use Nxp\Core\Utils\HTTP\Response;
use Nxp\Core\Utils\API\APIRequestHandler;
use Nxp\Core\Utils\Form\FormFactory;

class WelcomeController
{
    public function welcome()
    {

        $templatePath = __DIR__ . "/../../views/Welcome/Welcome.php";
        $engine = new TemplateEngine($templatePath);

        $filterHandler = $engine->getParser()->getFilterHandler();

        // Set up form and form elements using your existing code
        $form = FormFactory::startForm("Form1");
        $input = FormFactory::input('username');
        $input->addValidationRule('required')
            ->addValidationRule('minLength', 5)
            ->addValidationRule('maxLength', 15);

        $submitButton = FormFactory::submitButton('submit_button', 'Submit');

        // Set variables
        $engine->set('form', $form);
        $engine->set('input', $input);
        $engine->set('submitButton', $submitButton);
        $engine->set('title', 'OpenCAD');
        $engine->set('count', 5);

        $filterHandler->register('uppercase', function ($value) {
            return strtoupper($value);
        });


        // Render the template
        $output = $engine->render();
        echo $output;
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
