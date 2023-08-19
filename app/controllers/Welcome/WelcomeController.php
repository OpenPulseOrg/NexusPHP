<?php

namespace Nxp\Controllers\Welcome;

use Nxp\Core\Utils\HTTP\Request;
use Nxp\Core\Utils\HTTP\Response;
use Nxp\Core\Utils\Form\FormFactory;
use Nxp\Core\Utils\Service\Container\Container;
use Nxp\Core\Utils\Error\ErrorFactory;
use Nxp\Core\Templating\TemplateEngine;
use Nxp\Core\Utils\API\APIRequestHandler;

class WelcomeController
{
    public function welcome()
    {
        echo "Welcome to NexusPHP";
    }

}
