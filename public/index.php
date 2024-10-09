<?php

session_start();

define('BASE_DIR', dirname(__FILE__) . '/..');
define('SOURCE_DIR', BASE_DIR . '/src');
define('VIEW_DIR', SOURCE_DIR . '/views');
define('CONTROLLER_DIR', SOURCE_DIR . '/controllers');
define('MODEL_DIR', SOURCE_DIR . '/models');

require CONTROLLER_DIR . '/error.php';
require CONTROLLER_DIR . '/navigation.php';
require CONTROLLER_DIR . '/exercise_controller.php';

$_ENV = parse_ini_file(BASE_DIR . '/.env');

$redirect_uri = $_SERVER['REQUEST_URI'] ?? '/';
$redirect_uri = explode('?', $redirect_uri)[0];

switch ($_SERVER['REQUEST_METHOD']) {
	case 'GET':
		getRedirection($redirect_uri);
		break;
	case 'POST':
		postRedirection($redirect_uri);
		break;
	default:
		methodNotAllowed();
}

function getRedirection($redirect_uri)
{
	if (preg_match("/^\/exercises\/([0-9]+)$/", $redirect_uri, $str)) {
		deleteExercise($str[1]);
		return;
	}
	switch ($redirect_uri) {
		case '/':
			home();
			break;
		case '/exercises':
			manageExercises();
			break;
		case '/exercises/answering':
			takeAnExercises();
			break;
		case '/exercises/new':
			createAnExercises();
			break;
		default:
			lost();
	}
}

function postRedirection($redirect_uri)
{
	switch ($redirect_uri) {
		case '/exercises':
			createExercise();
			break;
		default:
			lost();
	}
}
