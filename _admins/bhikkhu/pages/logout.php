<?php
	session_unset();
	session_destroy();

	$request_url = pathinfo($_SERVER['REQUEST_URI']);
	$request_url_parts = explode('/', rtrim($request_url['dirname'], '/'));
	$url = 'http://' . $_SERVER['SERVER_NAME'] . '/' . $request_url_parts[1] . '/';

	header('Location: ' . $url);
	exit();