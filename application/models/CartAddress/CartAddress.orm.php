<?php
use NewFramework\Orm\JoinClause;

return [
    'entityClass' => \App\Model\CartAddress\CartAddress::class,
    'repositoryClass' => \App\Model\CartAddress\Repository::class,
    'tableName' => 'cart_address',
    'primaryKey' => 'id',
    'softDeletes' => false,
    'joins' => [
    ],
    'casts' => [
        'cart_id' => 'int',
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