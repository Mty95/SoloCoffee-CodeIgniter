<?php
use NewFramework\Orm\JoinClause;

return [
    'entityClass' => \App\Model\Order\Order::class,
    'repositoryClass' => \App\Model\Order\Repository::class,
    'tableName' => 'orders',
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
        'total_items' => 'int',
		'payment_data' => 'json-array',
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
