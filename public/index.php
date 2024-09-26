<?php

session_start();

define('BASE_DIR', dirname(__FILE__) . '/..');
define('SOURCE_DIR', BASE_DIR . '/src');
define('VIEW_DIR', SOURCE_DIR . '/views');
define('CONTROLLER_DIR', SOURCE_DIR . '/controllers');
define('MODEL_DIR', SOURCE_DIR . '/models');

require CONTROLLER_DIR . '/navigation.php';

$redirect_uri = $_SERVER['REQUEST_URI'] ?? '/';
$redirect_uri = explode('?', $redirect_uri)[0];

switch ($redirect_uri) {
	case '/':
		home();
		break;
	case '/exercises/answering':
		exercises_root();
		break;
	default:
		lost();
}
