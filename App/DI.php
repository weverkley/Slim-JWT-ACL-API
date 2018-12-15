<?php

use Slim\Container;

// middleware
$container['ACLMiddleware'] = function (Container $c) {
    return new App\Middleware\ACLMiddleware($c);
};

// services
$container['UserService'] = function (Container $c) {
    return new App\Service\UserService($c->get('pdo'));
};

// controllers
$container['HomeController'] = function (Container $c) {
	return new App\Controller\HomeController($c->get('renderer'));
};

$container['UserController'] = function (Container $c) {
	return new App\Controller\UserController($c->get('UserService'));
};

$container['AuthController'] = function (Container $c) {
	return new App\Controller\AuthController($c->get('UserService'));
};