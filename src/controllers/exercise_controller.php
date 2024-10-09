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

function changeStateOfExercise(int $id)
{
	if (!isset($_GET['exercise']['status'])) {
		badRequest();
		return;
	}

	$exercise = null;
	try {
		$exercise = new Exercises($id);
	} catch (Exception) {
		lost();
		return;
	}

	if ($exercise->getFieldsCount() < 1) {
		badRequest();
		return;
	}

	switch ($_GET['exercise']['status']) {
		case 'answering' && $exercise->getStatus() == Status::Building:
			$exercise->setExerciseAs(Status::Answering);
			break;
		case 'closed' && $exercise->getStatus() == Status::Answering:
			$exercise->setExerciseAs(Status::Closed);
			break;
		default:
			badRequest();
			return;
	}

	header('Location: /exercises');
}
