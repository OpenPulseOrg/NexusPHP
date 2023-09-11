<?php

namespace Nxp\Controllers\Welcome;

use Nxp\Core\Security\Cleaning\Validator;
use Nxp\Core\Utils\Session\Manager;

class WelcomeController
{
    public function welcome()
    {
        $validator = new Validator();

        $validator->validateAgeRange("04/04/1999", 50, 90);

        
        // Get the Manager instance and start the session
        $sessionManager = Manager::getInstance();
        $sessionManager->start();
        
        // Display all session details
        $sessionManager->varDump();
        var_dump($validator->getErrors());
    }
}
