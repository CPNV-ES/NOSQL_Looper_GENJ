<?php

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
