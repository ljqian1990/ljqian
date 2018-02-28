<?php
return [
    'master' => [
        'host' => '127.0.0.1',
        'port' => 3306,
        'database' => 'mysql',
        'user' => 'root',
        'password' => '',
        'charset' => 'utf8',
        'pconnect' => false
    ],
    'slave' => [
        [
            'host' => '127.0.0.1',
			'port' => 3306,
			'database' => 'mysql',
			'user' => 'root',
			'password' => '',
			'charset' => 'utf8',
			'pconnect' => false
        ]
    ]
];