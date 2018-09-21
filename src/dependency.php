<?php

use Slim\Container;

$container = $app->getContainer();

// view renderer
$container['renderer'] = function (Container $c) {
    $settings = $c->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};

// monolog
$container['logger'] = function (Container $c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], Monolog\Logger::DEBUG));
    return $logger;
};

// database connection
$container['pdo'] = function (Container $c) {
	try {
		$db = $c['settings']['db'];
		$pdo = new PDO('mysql:host=' . $db['host'] . ';dbname=' . $db['dbname'], $db['user'], $db['pass']);
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
		$pdo->exec("SET NAMES utf8; SET time_zone = 'America/Sao_Paulo'");
		return $pdo;
	} catch (Exception $e) {
		return $c['response']
            ->withStatus(500)
            ->withHeader('Content-Type', 'Application/json')
            ->withJson(['error' => true, 'message' => $e->getMessage()], 500);
	}
};

// Activating routes in a subfolder
$container['environment'] = function () {
    $scriptName = $_SERVER['SCRIPT_NAME'];
    $_SERVER['SCRIPT_NAME'] = dirname(dirname($scriptName)) . '/' . basename($scriptName);
    return new Slim\Http\Environment($_SERVER);
};

// Jwt container
$container['jwt'] = function () {
    return new StdClass;
};

// Slim cache
$container['cache'] = function () {
    return new \Slim\HttpCache\CacheProvider();
};