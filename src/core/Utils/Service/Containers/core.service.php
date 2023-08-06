<?php

use Nxp\Core\Security\Logging\Logger;
use Nxp\Core\Security\Monitoring\SystemChecks;
use Nxp\Core\Security\Server\Info;
use Nxp\Core\Utils\Tracking\PageTracker;

return [
    'logger' => [
        'resolver' => function ($container) {
            return new Logger(
                $container->get("queryFactory")
            );
        },
        'singleton' => true,
    ],

    'systemChecks' => [
        'resolver' => function ($container) {
            return new SystemChecks(
                $container->get('tableFactory')
            );
        },
        'singleton' => true,
    ],

    'serverInfo' => [
        'resolver' => function () {
            return new Info();
        },
        'singleton' => true
    ],
    'pageTracker' => [
        'resolver' => function () {
            return new PageTracker();
        },
        'singleton' => true
    ],
];
