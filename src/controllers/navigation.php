<?php

include_once MODEL_DIR . '/exercise.php';

function home()
{
	include VIEW_DIR . '/home.php';
}

function createAnExercises()
{
	include VIEW_DIR . '/create_an_exercise.php';
}

function takeAnExercises()
{
	$exercises = exercises::getExercises(Status::Answering);
	include VIEW_DIR . '/take_an_exercise.php';
}

function manageExercises()
{
	$buildingExercises = exercises::getExercises(Status::Building);
	$answeringExercises = exercises::getExercises(Status::Answering);
	$closeExercises = exercises::getExercises(Status::Closed);

	include VIEW_DIR . '/manage_an_exercise.php';
}
