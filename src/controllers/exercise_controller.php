<?php

include_once MODEL_DIR . '/exercise.php';

function createExercise()
{
	if (!isset($_POST['exercise_title'])) {
		badRequest();
		return;
	}

	$exercise = Exercises::create($_POST['exercise_title']);
	header('Location: /exercises/' . $exercise->getId() . '/fields');
}

function deleteExercise($id)
{
	try {
		$exercise = new Exercises($id);
	} catch (Exception $e) {
		lost();
		return;
	}

	if ($exercise->getExerciseStatus() == Status::Building->value || $exercise->getExerciseStatus() == Status::Closed->value) {
		$exercise->delete();
	}
	header('Location: /exercises');
}
