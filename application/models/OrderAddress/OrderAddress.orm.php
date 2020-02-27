<?php
use NewFramework\Orm\JoinClause;

return [
    'entityClass' => \App\Model\OrderAddress\OrderAddress::class,
    'repositoryClass' => \App\Model\OrderAddress\Repository::class,
    'tableName' => 'order_address',
    'primaryKey' => 'id',
    'softDeletes' => false,
    'joins' => [
    ],
    'casts' => [
        'order_id' => 'int',
    ],
    'validation' => [
        'rules' => [
        ],
        'titles' => [
        ],
        'messages' => [
        ],
    ],
];