<?php
return [
    'master' => [
        'host' => '192.168.101.220',
        'port' => 3306,
        'database' => 'clp',
        'user' => 'clpuser',
        'password' => 'cespondspassacclp',
        'charset' => 'utf8',
        'pconnect' => false
    ],
    'slave' => [
        [
            'host' => '192.168.101.220',
			'port' => 3306,
			'database' => 'clp',
			'user' => 'clpuser',
			'password' => 'cespondspassacclp',
			'charset' => 'utf8',
			'pconnect' => false
        ],
        [
			'host' => '192.168.101.220',
			'port' => 3306,
			'database' => 'clp',
			'user' => 'clpuser',
			'password' => 'cespondspassacclp',
			'charset' => 'utf8',
			'pconnect' => false
        ]
    ]
];