<?php
use NewFramework\Orm\JoinClause;

return [
    'entityClass' => \App\Model\User\User::class,
    'repositoryClass' => \App\Model\User\Repository::class,
    'tableName' => 'users',
    'primaryKey' => 'id',
    'softDeletes' => false,
    'joins' => [
    ],
    'casts' => [
        'active' => 'int',
    ],
    'validation' => [
        'rules' => [
        	'login' => [
        		'username' => 'trim|required|min_length[4]|exists[users.username]',
        		'password' => 'trim|required|min_length[4]',
			],
			'register' => [
				'username' => 'trim|required|min_length[4]|is_unique[users.username]',
				'password' => 'trim|required|min_length[4]',
			],
			'update-profile' => [
				'email' => 'trim|valid_email|is_unique[users.email]',
				'password' => 'trim|min_length[4]',
			],
        ],
        'titles' => [
        ],
        'messages' => [
        ],
    ],
];
