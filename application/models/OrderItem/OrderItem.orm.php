<?php
use NewFramework\Orm\JoinClause;

return [
    'entityClass' => \App\Model\OrderItem\OrderItem::class,
    'repositoryClass' => \App\Model\OrderItem\Repository::class,
    'tableName' => 'order_items',
    'primaryKey' => 'id',
    'softDeletes' => false,
    'joins' => [
		(new JoinClause('product_slug', 'slug'))->on('(main).product_id = products.id')->direction('LEFT'),
		(new JoinClause('product_name', 'name'))->on('(main).product_id = products.id')->direction('LEFT'),
		(new JoinClause('product_image', 'image'))->on('(main).product_id = products.id')->direction('LEFT'),
    ],
    'casts' => [
        'order_id' => 'int',
        'product_id' => 'int',
        'qty' => 'int',
        'price' => 'float',
        'total_price' => 'float',
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
