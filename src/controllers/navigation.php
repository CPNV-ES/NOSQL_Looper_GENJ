<?php

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
	include VIEW_DIR . '/exercises_root.php';
}

function lost()
{
	include VIEW_DIR . '/errors/lost.php';
}

function bad_request()
{
	include VIEW_DIR . '/errors/bad_request.php';
}

function method_not_allowed()
{
	include VIEW_DIR . '/errors/method_not_allowed.php';
}

function manage()
{
	include VIEW_DIR . '/manage.php';
}
