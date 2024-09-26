<?php

include_once MODEL_DIR . '/exercise.php';

function home()
{
	include VIEW_DIR . '/home.php';
}

function create_an_exercises()
{
	include VIEW_DIR . '/create_an_exercise.php';
}

function exercises_root()
{
	$exercises = exercises::getExercises(Status::Answering);
	include VIEW_DIR . '/exercises_root.php';
}

function manage()
{
	include VIEW_DIR . '/manage.php';
}
