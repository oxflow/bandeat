<?php

namespace Lib;

class						Session
{
	protected static		$_started = false;

	public static function	init()
	{
		if (self::$_started !== true)
		{
			session_start();
			self::$_started = true;
		}
	}

	public static function	destroy($key = NULL)
	{
		if ($key !== NULL)
		{
			if (!is_array($key))
				unset($_SESSION[$key]);
			else
				foreach ($key as $var)
					unset($_SESSION[$var]);
		}
		else if (self::$_started === true)
		{
			session_unset();
			session_destroy();
			self::$_started = false;
		}
	}

	public static function	get($key)
	{
		if (isset($_SESSION[$key]))
			return ($_SESSION[$key]);
		return false;
	}

	public static function	reset(array $data)
	{
		$data = array_merge($_SESSION, $data);
		session_unset();
		self::set($data);
	}

	public static function	set($key, $value = NULL)
	{
		if (is_array($key))
			foreach ($key as $k => $val)
				$_SESSION[$k] = $val;
		else if (is_string($key) AND $value !== NULL)
			$_SESSION[$key] = $value;
	}

	public static function	hasFlash()
	{
		return isset($_SESSION['flash']) ? true : false;
	}

	public static function	getFlash()
	{
		$flash = isset($_SESSION['flash']) ? $_SESSION['flash'] : false;
		unset($_SESSION['flash']);
		return $flash;
	}

	public static function	flash($value)
	{
		$_SESSION['flash'] = $value;
	}
}