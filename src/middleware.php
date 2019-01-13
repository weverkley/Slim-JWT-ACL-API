<?php

use Tuupola\Middleware\JwtAuthentication;
use \Slim\HttpCache\Cache;

// jwt validation
$app->add(new JwtAuthentication([
	// Uncomment these lines if you want to use a custom header
	// Default: Authorization Bearer
	// 'header' => 'App-token',
	// 'cookie' => 'App-token',
    // 'regexp' => '/(.*)/',
    'path' => ['/v1'],
    'ignore' => ['/v1/auth/user'],
    'logger' => $container['logger'],
	'attribute' => 'jwt',
	'secure' => false,
    'realm' => 'Protected',
	'algorithm' => [ 'HS256' ],
	'secret' => (string)getenv('JWT_SECRET'),
	// 'before' => function ($request, $arguments) {
    //     return $request->withAttribute('test', 'test');
    // },
	'after' => function ($response, $arguments) {
		$container['jwt'] = $arguments['decoded'];
    },
	'error' => function ($response, $arguments) {
        throw new Exception($arguments['message'], 401);
    }
]));

// Cache middleware
$app->add(new Cache('public', 86400));
