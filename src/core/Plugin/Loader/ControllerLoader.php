<?php

namespace Nxp\Core\Plugin\Loader;

class ControllerLoader
{
    public function loadControllers($controllerPath)
    {
        if (is_dir($controllerPath)) {
            $controllerFiles = glob($controllerPath . '/*.php');
            foreach ($controllerFiles as $controllerFile) {
                include_once($controllerFile);
            }
        }
    }
}
