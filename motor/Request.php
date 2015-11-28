<?php

namespace Lib;

class					Request
{
	public function		postData($key, $secure = true)
	{
		if ($secure === true)
			return isset($_POST[$key]) ? htmlspecialchars($_POST[$key], ENT_QUOTES) : NULL;
		else
			return isset($_POST[$key]) ? $_POST[$key] : NULL;
	}

	public function		postExist($key)
	{
		return isset($_POST[$key]);
	}

	public function		getData($key, $secure = true)
	{
		if ($secure === true)
			return isset($_GET[$key]) ? htmlspecialchars($_GET[$key], ENT_QUOTES) : NULL;
		else
			return isset($_GET[$key]) ? $_GET[$key] : NULL;
	}

	public function		getExist($key)
	{
		return isset($_GET[$key]);
	}

	public function		cookieData($key)
	{
		return isset($_COOKIE[$key]) ? $_COOKIE[$key] : NULL;
	}

	public function		cookieExist($key)
	{
		return isset($_COOKIE[$key]);
	}

	public function		method()
	{
		return isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET';
	}

	public function		uri()
	{
		return $_SERVER['REQUEST_URI'];
	}

	public function		protocol()
	{
		return $_SERVER['SERVER_PROTOCOL'];
	}
}