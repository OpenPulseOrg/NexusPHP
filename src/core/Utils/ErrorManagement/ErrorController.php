<?php

namespace Nxp\Core\Utils\ErrorManagement;

/**
 * Class ErrorController
 *
 * This class handles the display of the 404 error page.
 * 
 * @package Nxp\Core\Utils\ErrorManagement
 */
class ErrorController
{
    /**
     * Displays the 404 error page.
     *
     * @return void
     */
    public function error404()
    {
        include_once VIEWS_ROOT_PATH . 'Errors/404.php';
    }

    /**
     * Displays the 500 error page.
     *
     * @return void
     */
    public function error500()
    {
        include_once VIEWS_ROOT_PATH . "Errors/500.php";
    }
}
