<?php

use Tuupola\Middleware\JwtAuthentication;
use \Slim\HttpCache\Cache;

// JWT validation
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

// CORS
$app->options('/{routes:.+}', function ($request, $response, $args) {
	return $response;
});

// CORS
$app->add(function ($req, $res, $next) {
	$response = $next($req, $res);
	return $response
		->withHeader('Access-Control-Allow-Origin', '*')
		->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
		->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
});

// Fix token not found for undefined route
// display error for route not found before token check
$app->add(function ($request, $response, $next) {
	$route = $request->getAttribute("route");

	if (empty($route)) {
		// throw new Slim\Exception\NotFoundException($request, $response);
		$return['error'] = true;
		$return['message'] = 'Undefined route or page not found.';
		$return['data'] = [];
		return $response->withJson($return)->withStatus(404);
	}

	return $next($request, $response);
});
