<?php

function lost()
{
	include VIEW_DIR . '/errors/lost.php';
}

function badRequest()
{
	include VIEW_DIR . '/errors/bad_request.php';
}

function serverError()
{
	include VIEW_DIR . '/errors/server_error.php';
}
