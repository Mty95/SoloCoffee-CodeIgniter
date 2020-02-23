<?php
use NewFramework\Orm\JoinClause;

return [
    'entityClass' => \App\Model\Category\Category::class,
    'repositoryClass' => \App\Model\Category\Repository::class,
    'tableName' => 'categories',
    'primaryKey' => 'id',
    'softDeletes' => false,
    'joins' => [
    ],
    'casts' => [
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