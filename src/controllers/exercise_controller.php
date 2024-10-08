<?php

include_once MODEL_DIR . '/exercise.php';

$entry = [
	'ExerciseController()' => [
		'POST' => [
			'/exercises' => 'createExercise()'
		]
	]
];

class ExerciseController
{
	public function createExercise()
	{
		if (!isset($_POST['exercise_title'])) {
			badRequest();
			return;
		}

		$exercise = Exercise::create($_POST['exercise_title']);
		header('Location: /exercises/' . $exercise->getId() . '/fields');
	}
}
