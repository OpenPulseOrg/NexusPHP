<?php

use Nxp\Core\Database\Database;
use Nxp\Core\Database\Factories\Batch;
use Nxp\Core\Database\Factories\Query;
use Nxp\Core\Database\Factories\Table;
use Nxp\Core\Database\Factories\Transaction;

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
    ],

    'batchFactory' => [
        'resolver' => function ($container) {
            return new Batch($container);
        },
        'singleton' => true,
    ]
];
