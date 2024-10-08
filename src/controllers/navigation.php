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
		$exercises = Exercise::getExercises(Status::Answering);
		include VIEW_DIR . '/take_an_exercise.php';
	}

	public function manageExercises()
	{
		$buildingExercises = Exercise::getExercises(Status::Building);
		$answeringExercises = Exercise::getExercises(Status::Answering);
		$closeExercises = Exercise::getExercises(Status::Closed);

		include VIEW_DIR . '/manage_an_exercise.php';
	}
}
