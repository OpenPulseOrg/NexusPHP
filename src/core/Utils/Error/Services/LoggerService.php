<?php

namespace Nxp\Core\Utils\Error\Services;

use Nxp\Core\Database\Query;
use Nxp\Core\Security\Logging\Logger;

class LoggerService
{
    private $logger;

    public function __construct($container)
    {
        $queryFactory = new Query($container);
        $this->logger = new Logger($queryFactory);
    }

    public function log($level, $type, $details)
    {
        $this->logger->log($level, $type, $details);
    }
}
