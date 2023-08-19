<?php

use Nxp\Core\Security\Auth\Authentication;
use Nxp\Core\Utils\Service\Container\Container;

class DatabaseCron{
    public function runCron(){
        $authentication = new Authentication(Container::getInstance());
        $authentication->createUsersTableIfNotExists();
    }
}