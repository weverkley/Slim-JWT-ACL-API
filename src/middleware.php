<?php

use Tuupola\Middleware\JwtAuthentication;
use \Slim\HttpCache\Cache;

// jwt validation
$app->add(new JwtAuthentication([
	'header' => 'App-token',
	'cookie' => 'App-token',
    'regexp' => '/(.*)/',
    'path' => ['/v1'],
    'ignore' => ['/v1/auth/user'],
    'logger' => $container['logger'],
	'attribute' => 'jwt',
	'secure' => false,
    'realm' => 'Protected',
    'algorithm' => [ 'HS256' ],
	'secret' => (string)getenv('JWT_SECRET'),
	'after' => function ($response, $arguments) {
		$container['jwt'] = $arguments['decoded'];
    },
	'error' => function ($response, $arguments) {
        $data['error'] = true;
		$data['message'] = 'JWT access denied. '.$arguments['message'];
		$data['arguments'] = $arguments;
		return $response
            ->withHeader('Content-Type', 'Application/json')
            ->withJson($data, 401);
    }
]));

// Cache middleware
$app->add(new Cache('public', 86400));
