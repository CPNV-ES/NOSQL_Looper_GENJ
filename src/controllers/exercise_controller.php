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

function setExerciseAsAnswering(int $id)
{
	$exercise = new Exercises($id);
	if ($exercise->getStatus() === Status::Building)
		$exercise->setExerciseAs(Status::Answering);
	header('Location: /exercises');
}
function setExerciseAsClosed(int $id)
{
	$exercise = new Exercises($id);
	if ($exercise->getStatus() === Status::Answering)
		$exercise->setExerciseAs(Status::Closed);
	header('Location: /exercises');
}
