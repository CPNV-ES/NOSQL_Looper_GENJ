<?php

define('BASE_DIR', dirname(__FILE__) . '/../..');
define('SOURCE_DIR', BASE_DIR . '/src');
define('VIEW_DIR', SOURCE_DIR . '/views');
define('CONTROLLER_DIR', SOURCE_DIR . '/controllers');
define('MODEL_DIR', SOURCE_DIR . '/models');

require BASE_DIR . '/vendor/autoload.php';
require_once MODEL_DIR . '/exceptions/looper_exception.php';
require_once MODEL_DIR . '/exercise.php';

while (true) {
	sleep(5);
	print 'Checking exercises to close' . "\n";
	$_ENV = parse_ini_file(BASE_DIR . '/.env');

	$exercises = Exercise::fromLimitDateAndIsAnswering();

	foreach ($exercises as $exercise) {
		print 'Closing exercise ' . $exercise->getId() . "\n";
		$exercise->setExerciseAs(Status::Closed);
	}
}
