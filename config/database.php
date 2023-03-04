<?php

return [
	'default' => 'mysql',
	'connections' => [
		'mysql' => [
			'host' => env('DB_HOST'),
	            	'driver' => 'mysql',      
            		'database' => env('DB_DATABASE'),
            		'username' => env('DB_USERNAME'),
            		'password' => env('DB_PASSWORD'),
            		'charset'   => 'utf8',
            		'collation' => 'utf8_unicode_ci',
		],
	],
];
