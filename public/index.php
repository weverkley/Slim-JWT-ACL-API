<?php

// Allow from any origin
if (isset($_SERVER['HTTP_ORIGIN'])) {
    // should do a check here to match $_SERVER['HTTP_ORIGIN'] to a
    // whitelist of safe domains
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');    // cache for 1 day
}
// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

}

// Composer autoload dependencies
$autoload = '../vendor/autoload.php';
if (file_exists($autoload)) {
    require $autoload;
} else {
    echo 'File: ' . $autoload . ' doesnt exist';
    echo 'You must install composer first: composer install';
    exit;
}

// Load .env file
$dotenv = new Dotenv\Dotenv('../');
$dotenv->load();

if ((bool) getenv('API_DEBUG')) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

if (PHP_SAPI == 'cli-server') {
    // To help the built-in PHP dev server, check if the request was actually for
    // something which should probably be served as a static file
    $url = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . $url['path'];
    if (is_file($file)) {
        return false;
    }
}

session_start();

// Instantiate the app
$settings = require '../src/settings.php';
$app = new \Slim\App($settings);

// Set up app
require '../src/dependency.php';

// Register middleware
require '../src/middleware.php';

// Set up errors
require '../src/errors.php';

// Set up DI
require '../App/DI.php';

// Register routes
require '../App/routes.php';

// Run app
$app->run();
