<?php

include_once MODEL_DIR . '/exercise.php';

function createExercise()
{
	if (!isset($_POST['exercise_title'])) {
		bad_request();
		return;
	}

	$Exercise = exercises::create($_POST['exercise_title']);
	header('Location: /exercises/' . $Exercise->getId() . '/fields');
}
