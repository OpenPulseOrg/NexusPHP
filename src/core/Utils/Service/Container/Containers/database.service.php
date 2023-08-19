<?php

use Nxp\Core\Database\Query;
use Nxp\Core\Database\Table;
use Sentry\Tracing\Transaction;


return [
    'database' => [
        'resolver' => function () {
            return Database::getInstance()->connect();
        },
        'singleton' => true,
    ],

    'pdo' => [
        'resolver' => function () {
            return Database::getInstance()->connect();
        },
        'singleton' => true,
    ],
    
    'queryFactory' => [
        'resolver' => function ($container) {
            return new Query($container);
        },
        'singleton' => true,
    ],

    'tableFactory' => [
        'resolver' => function ($container) {
            return new Table($container);
        },
        'singleton' => true,
    ],

    'transactionFactory' => [
        'resolver' => function ($container) {
            return new Transaction($container);
        },
        'singleton' => true,
    ]
];
