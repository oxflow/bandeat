<?php

namespace Lib;

class					ManagerFactory
{
	private				$dao;
	private				$managers = array();

	public function		__construct()
	{
		try
		{
			$this->dao = new \PDO('mysql:host=localhost:3306;dbname=framework0', 'root', '123456');
			$this->dao->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
			$this->dao->exec("SET CHARACTER SET UTF8");
		}
		catch (\PDOException $e)
		{
			throw new \RuntimeException('Impossible to connect to the database');
		}
	}

	public function		getManager($name)
	{
		if (!is_string($name))
			throw new \InvalidArgumentException('Name must be a string');
		if (!isset($this->managers[$name]))
		{
			$class = '\\Managers\\' . $name . 'Manager';
			$this->managers[$name] = new $class($this->dao);
		}
		return $this->managers[$name];
	}
}