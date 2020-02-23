<?php
use NewFramework\Orm\JoinClause;

return [
    'entityClass' => \App\Model\Product\Product::class,
    'repositoryClass' => \App\Model\Product\Repository::class,
    'tableName' => 'products',
    'primaryKey' => 'id',
    'softDeletes' => false,
    'joins' => [
		(new JoinClause('category_name', 'name'))->on('(main).category_id = categories.id'),
    ],
    'casts' => [
        'category_id' => 'int',
        'price' => 'float',
        'special_price' => 'float',
        'stock' => 'int',
        'active' => 'bool',
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
