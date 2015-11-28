<?php

namespace Lib\ORM;

class							ORM
{
	private						$handler;
	private						$dbname;
	private						$host;
	private						$login;
	private						$password;

	public static				$instance;

	public static function		getInstance()
	{
		if (!isset(self::$instance))
		{
			self::$instance = new self;
		}
		return self::$instance;
	}

	public function				db_select($sql, $vars = array(), &$rows = NULL)
	{
		if (!is_array($vars))
			return false;
		$req = $this->handler->prepare($sql);
		if (!empty($vars))
			$req->execute($vars);
		else
			$req->execute();
		$data = $req->fetchAll();
		$req->closeCursor();
		foreach ($data as $kd => $vd)
		{
			foreach ($vd as $kvd => $vvd)
			{
				if (is_int($kvd))
					unset($data[$kd][$kvd]);
			}
		}
		if (isset($rows))
			$rows = count($data);
		return $data;
	}

	public function				db_execute($sql, $vars = array(), &$rows = NULL)
	{
		if (!is_array($vars))
			return false;
		$req = $this->handler->prepare($sql);
		if (!empty($vars))
			$req->execute($vars);
		else
			$req->execute();
		$req->closeCursor();
		if (isset($rows))
			$rows = count($data);
		return true;
	}

	final private function		__construct()
	{
		$this->host = 'localhost';
		$this->login = 'root';
		$this->password = '123456';
		$this->dbname = 'framework1';
		try
		{
			$connexion_str = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
			$this->handler = new \PDO($connexion_str, $this->login, $this->password);
			$this->handler->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
			$this->handler->exec("SET CHARACTER SET UTF8");
		}
		catch (\PDOException $e)
		{
			throw new \RuntimeException('Impossible to connect to the database');
		}
	}

	final private function		__clone()
	{
	}
}