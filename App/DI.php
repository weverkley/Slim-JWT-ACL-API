<?php

use App\Controller\HomeController;
use App\Controller\UserController;
use App\Controller\AuthController;

use App\Service\UserService;

use App\Middleware\ACLMiddleware;

use Slim\Container;

// middleware
$container['ACLMiddleware'] = function (Container $c) {
    return new ACLMiddleware($c);
};

// services
$container['UserService'] = function (Container $c) {
    return new UserService($c->get('pdo'));
};

// controllers
$container['HomeController'] = function (Container $c) {
	return new HomeController($c->get('renderer'));
};

$container['UserController'] = function (Container $c) {
	return new UserController($c->get('UserService'));
};

$container['AuthController'] = function (Container $c) {
	return new AuthController($c->get('UserService'));
};