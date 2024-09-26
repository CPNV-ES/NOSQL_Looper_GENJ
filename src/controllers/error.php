<?php

function lost()
{
	include VIEW_DIR . '/errors/lost.php';
}

function badRequest()
{
	include VIEW_DIR . '/errors/bad_request.php';
}

function methodNotAllowed()
{
	include VIEW_DIR . '/errors/method_not_allowed.php';
}
