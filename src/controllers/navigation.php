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
	include VIEW_DIR . '/exercises_root.php';
}

function manageExercises()
{
	include VIEW_DIR . '/manage.php';
}
