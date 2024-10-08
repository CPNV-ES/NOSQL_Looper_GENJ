<?php

session_start();

define('BASE_DIR', dirname(__FILE__) . '/..');
define('SOURCE_DIR', BASE_DIR . '/src');
define('VIEW_DIR', SOURCE_DIR . '/views');
define('CONTROLLER_DIR', SOURCE_DIR . '/controllers');
define('MODEL_DIR', SOURCE_DIR . '/models');

require MODEL_DIR . '/router.php';

$_ENV = parse_ini_file(BASE_DIR . '/.env');

$request_uri = $_SERVER['REQUEST_URI'] ?? '/';
$request_uri = explode('?', $request_uri)[0];

$router = new Router(CONTROLLER_DIR);

try {
	if (!$router->run($_SERVER['REQUEST_METHOD'], $request_uri)) {
		lost();
	}
} catch (\Throwable) {
	serverError();
}
