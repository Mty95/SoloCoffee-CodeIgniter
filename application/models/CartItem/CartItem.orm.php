<?php
use NewFramework\Orm\JoinClause;

return [
    'entityClass' => \App\Model\CartItem\CartItem::class,
    'repositoryClass' => \App\Model\CartItem\Repository::class,
    'tableName' => 'cart_items',
    'primaryKey' => 'id',
    'softDeletes' => false,
    'joins' => [
		(new JoinClause('product_slug', 'slug'))->on('(main).product_id = products.id')->direction('LEFT'),
		(new JoinClause('product_name', 'name'))->on('(main).product_id = products.id')->direction('LEFT'),
		(new JoinClause('product_image', 'image'))->on('(main).product_id = products.id')->direction('LEFT'),
    ],
    'casts' => [
        'cart_id' => 'int',
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
