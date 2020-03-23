<?php
use NewFramework\Orm\JoinClause;

return [
    'entityClass' => \App\Model\CustomerAddress\CustomerAddress::class,
    'repositoryClass' => \App\Model\CustomerAddress\Repository::class,
    'tableName' => 'customer_address',
    'primaryKey' => 'id',
    'softDeletes' => true,
    'joins' => [
    ],
    'casts' => [
        'user_id' => 'int',
		'map_position' => 'json',
    ],
    'validation' => [
        'rules' => [
        	'create' => [
        		'user_id' => 'trim|required|numeric|exists[users.id]',
				'name' => 'trim|required',
				'dni' => 'trim|required|min_length[8]|max_length[11]',
				'cellphone' => 'trim|required|min_length[9]',
				'line1' => 'trim|required|min_length[10]',
				'line2' => 'trim|required|min_length[4]',
//				'map_position' => 'trim|required', // Parece que no funciona cuando se envía un array
			],
			'update' => [
				'name' => 'trim|required',
				'dni' => 'trim|required|min_length[8]|max_length[11]',
				'cellphone' => 'trim|required|min_length[9]',
				'line1' => 'trim|required|min_length[10]',
				'line2' => 'trim|required|min_length[4]',
//				'map_position' => 'trim|required', // Parece que no funciona cuando se envía un array
			],
        ],
        'titles' => [
        ],
        'messages' => [
        ],
    ],
];
