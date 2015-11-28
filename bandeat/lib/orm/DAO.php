<?php

namespace ORM;

class								DAO
{
	const							DB_HOST = 'localhost';
	const							DB_USER = 'root';
	const							DB_PASS = '123456';
	const							DB_NAME = 'framework1';

	public							$errno;
	private							$_handler;

	public static					$instance = NULL;

	// Exécute une requête
	public function		exec($request)
	{
		if (!is_string($request) || empty($request))
		{
			throw new Exception('Variable "$request" must be a non-empty string');
		}
	}

	// Interroge la base de données
	public function		query($request)
	{
		if (!is_string($request) || empty($request))
		{
			throw new Exception('Variable "$request" must be a non-empty string');
		}
	}

	public final static function	getInstance()
	{
		if (!isset(self::$instance))
		{
			self::$instance = new self;
		}
		return self::$instance;
	}

	private final function			__construct()
	{
		$this->_handler = new \mysqli(self::DB_HOST, self::DB_USER, self::DB_PASS, self::DB_NAME);
		$this->errno = $this->_handler->connect_errno;
	}
}