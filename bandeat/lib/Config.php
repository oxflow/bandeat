<?php

namespace Lib;

class							Config
{
	protected static			$defines = array();

	public static function		add($name, $value = NULL)
	{
		if (is_array($name))
		{
			$definitons = array();
			foreach ($name as $ka => $va)
			{
				if (is_string($ka) || is_numeric($ka))
					$definitions[$ka] = $va;
				else
					throw new \RuntimeException('Definition key must be string or numeric');
			}
		}
		else if (is_string($name) || is_numeric($name))
			$definitions = array($name => $value);
		else
			throw new \RuntimeException('Definition key must be string or numeric');
		foreach ($definitions as $ka => $va)
		{
			define($ka, $va);
			self::$defines[$ka] = $va;
		}
		return true;
	}

	public static function		has($key)
	{
		if (defined($key) && isset(self::$defines[$key]))
			return TRUE;
		return FALSE;
	}

	public static function		get($key)
	{
		if (defined($key) && isset(self::$defines[$key]))
			return self::$defines[$key];
		return NULL;
	}

	private final function		__construct()
	{
	}
}
