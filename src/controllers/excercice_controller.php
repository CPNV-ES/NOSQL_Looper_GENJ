<?php

include_once MODEL_DIR . '/excercice.php';

function createExercice()
{
	if (!isset($_POST['exercise_title'])) {
		bad_request();
		return;
	}

	$exercice = Excercices::create($_POST['exercise_title']);
	header('Location: /exercises/' . $exercice->getId() . '/fields');
}
