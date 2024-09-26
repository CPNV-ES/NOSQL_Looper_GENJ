<?php

session_start();

define('BASE_DIR', dirname(__FILE__) . '/..');
define('SOURCE_DIR', BASE_DIR . '/src');
define('VIEW_DIR', SOURCE_DIR . '/views');
define('CONTROLLER_DIR', SOURCE_DIR . '/controllers');
define('MODEL_DIR', SOURCE_DIR . '/models');

require CONTROLLER_DIR . '/navigation.php';
require CONTROLLER_DIR . '/exercise_controller.php';

$_ENV = parse_ini_file(BASE_DIR . '/.env');

$redirect_uri = $_SERVER['REQUEST_URI'] ?? '/';
$redirect_uri = explode('?', $redirect_uri)[0];

switch ($_SERVER['REQUEST_METHOD']) {
	case 'GET':
		get_redirection($redirect_uri);
		break;
	case 'POST':
		post_redirection($redirect_uri);
		break;
	default:
		method_not_allowed();
}

function get_redirection($redirect_uri)
{
	switch ($redirect_uri) {
		case '/':
			home();
			break;
		case '/exercises':
			manage();
			break;
		case '/exercises/answering':
			exercises_root();
			break;
		case '/exercises/new':
			create_an_exercises();
			break;
		default:
			lost();
	}
}

function post_redirection($redirect_uri)
{
	switch ($redirect_uri) {
		case '/exercises':
			createExercise();
			break;
		default:
			lost();
	}
}
