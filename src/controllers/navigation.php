<?php

include_once MODEL_DIR . '/exercise.php';

$entry = [
	'Navigation()' => [
		'GET' => [
			'/' => 'home()',
			'/exercises' => 'manageExercises()',
			'/exercises/answering' => 'takeAnExercises()',
			'/exercises/new' => 'createAnExercises()'
		]
	]
];

class Navigation
{
	public function home()
	{
		include VIEW_DIR . '/home.php';
	}

	public function createAnExercises()
	{
		include VIEW_DIR . '/create_an_exercise.php';
	}

	public function takeAnExercises()
	{
	$exercises = exercises::getExercises(Status::Answering);
		include VIEW_DIR . '/take_an_exercise.php';
	}

	public function manageExercises()
	{
	$buildingExercises = exercises::getExercises(Status::Building);
	$answeringExercises = exercises::getExercises(Status::Answering);
	$closeExercises = exercises::getExercises(Status::Closed);

		include VIEW_DIR . '/manage_an_exercise.php';
	}
}
