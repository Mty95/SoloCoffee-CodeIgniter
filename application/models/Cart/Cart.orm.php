<?php
use NewFramework\Orm\JoinClause;

return [
    'entityClass' => \App\Model\Cart\Cart::class,
    'repositoryClass' => \App\Model\Cart\Repository::class,
    'tableName' => 'carts',
    'primaryKey' => 'id',
    'softDeletes' => false,
    'joins' => [
    ],
    'casts' => [
        'user_id' => 'int',
        'subtotal' => 'float',
        'discount' => 'float',
        'shipping' => 'float',
        'total' => 'float',
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