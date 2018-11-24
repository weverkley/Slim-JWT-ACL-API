<?php
define('PUBLIC_FOLDER', realpath(dirname(__FILE__)));
define('ROOT_DIR', explode('/public', PUBLIC_FOLDER)[0]);
define('DS', DIRECTORY_SEPARATOR);

// Allow from any origin
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    // header('Access-Control-Max-Age: 86400');    // cache for 1 day
}

// Angular request options
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']) && (
        $_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'] == 'POST' ||
        $_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'] == 'DELETE' ||
        $_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'] == 'PUT')
    ) {
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS'); // http://stackoverflow.com/a/7605119/578667
    }

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type, Accept, Origin, App-token');
    }

    exit;
}

// Composer autoload dependencies
$autoload = ROOT_DIR . DS . 'vendor' . DS . 'autoload.php';
if (file_exists($autoload)) {
    require $autoload;
} else {
    echo 'File: ' . $autoload . ' doesnt exist';
    echo 'You must install composer first: composer install';
    exit;
}

// Load .env file
$dotenv = new Dotenv\Dotenv(ROOT_DIR);
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
$settings = require ROOT_DIR . DS . 'src' . DS . 'settings.php';
$app = new \Slim\App($settings);

// Set up app
require ROOT_DIR . DS . 'src' . DS . 'dependency.php';

// Register middleware
require ROOT_DIR . DS . 'src' . DS . 'middleware.php';

// Set up errors
require ROOT_DIR . DS . 'src' . DS . 'errors.php';

// Set up DI
require ROOT_DIR . DS . 'App' . DS . 'DI.php';

// Register routes
require ROOT_DIR . DS . 'App' . DS . 'routes.php';

// Run app
$app->run();
