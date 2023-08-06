<?php

namespace Nxp\Core\Common\Abstracts\Models;

use Nxp\Core\Utils\Service\Container;

abstract class BaseModel
{
    protected $table;
    protected $container;

    public function __construct($tableName = null)
    {
        $this->table = $tableName;
        $this->container = Container::getInstance();
    }

    protected function getContainer()
    {
        return $this->container;
    }
}
