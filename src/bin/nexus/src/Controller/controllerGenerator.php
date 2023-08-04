<?php

namespace Controller;

use Nxp\Core\Security\Storage\FileSystem\Directory\DirectoryManager;
use QuestionHelper;

class controllerGenerator
{
    public function generate()
    {
        $questionHelper = new QuestionHelper();

        $namespace = $questionHelper->askWithInfo("Enter Namespace: ", "Namespace will be prefixed with: \\Nxp\\Controllers\\");

        $controllerDir = __DIR__ . "/../../../../../app/controllers";
        DirectoryManager::createFolder($controllerDir, $namespace);

        $fullNamespace = "Nxp\\Controllers\\" . $namespace;

        $className = $questionHelper->ask("Enter Classname: ");

        $classNameDir = $controllerDir . "/" . $namespace . "/" . $className . ".php";

        $template = <<<PHP
        <?php

        namespace $fullNamespace;

        class $className
        {
            // Create your controller methods here
        
            public function index()
            {
                // Index is the standard called class although, can be renamed.
            }
        }
        PHP;

        $filename = $classNameDir;

        if (!file_exists($filename)) {
            file_put_contents($filename, $template);
            $questionHelper->output("Controller file '$className.php' generated successfully.\n", "green");
        } else {
            $questionHelper->output("Controller file '$className.php' already exists.\n", "red");
        }
    }
}
