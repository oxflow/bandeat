<?php

namespace Lib;

class								DAO
{
	const							DB_HOST = 'localhost';
	const							DB_USER = 'root';
	const							DB_PASS = '123456';
	const							DB_NAME = 'framework1';
	const							VALUE_INT = 0;
	const							VALUE_DBL = 1;
	const							VALUE_STR = 2;
	const							VALUE_TAB = 3;
	const							VALUE_NUL = 4;

	public							$errno;
	public							$last_query = '';

	private							$_handler = null;
	private							$_query = '';
	private							$_arguments = array();
	private							$_nbArgsUndefined = 0;
	private							$_nbArgsNamed = 0;
	private							$_argNames = array();

	public static					$instances = array();

	public function					query()
	{
		if (empty($this->_query))
			throw new \RuntimeException('Query must be defined before with prepare() method in D::query()');
		$this->_bindValues();
		$result = mysqli_query($this->_handler, $this->_query);
		$this->last_query = $this->_query;
		if ($result === false)
			return false;
		mysqli_free_result($result);
		$this->_reset();
		return true;
	}

	public function					select()
	{
		if (empty($this->_query))
			throw new \RuntimeException('Query must be defined before with prepare() method in D::select()');
		$this->_bindValues();
		$result = mysqli_query($this->_handler, $this->_query);
		$this->last_query = $this->_query;
		if ($result === false)
			return false;
		$values = mysqli_fetch_all($result, MYSQLI_ASSOC);
		mysqli_free_result($result);
		$this->_reset();
		return $values;
	}

	public function					prepare($request, array $arguments = array())
	{
		if (empty($request) || !is_string($request))
			throw new \InvalidArgumentException('Variable "$request" must be a non-empty string');
		$this->_query = $request;
		$this->_parseArguments($request);
		$this->_arguments = $arguments;
	}

	public function					bind($name, $value, $type = self::VALUE_INT)
	{
		$accepted_values = array(
			self::VALUE_INT,
			self::VALUE_DBL,
			self::VALUE_STR,
			self::VALUE_TAB,
			self::VALUE_NUL
		);
		if (!in_array($type, $accepted_values))
			throw new \InvalidArgumentException('Value must be one of this types : int, double, string, array or null');
		if ($type === self::VALUE_INT && !is_int($value))
			throw new \InvalidArgumentException(sprintf('Argument type of "%s" must be integer', $name));
		else if ($type === self::VALUE_DBL && !is_float($value))
			throw new \InvalidArgumentException(sprintf('Argument type of "%s" must be double', $name));
		else if ($type === self::VALUE_STR && !is_string($value))
			throw new \InvalidArgumentException(sprintf('Argument type of "%s" must be string', $name));
		else if ($type === self::VALUE_TAB && !is_array($value))
			throw new \InvalidArgumentException(sprintf('Argument type of "%s" must be array', $name));
		else if ($type === self::VALUE_NUL && $value !== null)
			throw new \InvalidArgumentException(sprintf('Argument type of "%s" must be null', $name));
		$this->_arguments[$name] = $value;
		return $this;
	}

	public final static function	getInstance($host = self::DB_HOST, $user = self::DB_USER, $pass = self::DB_PASS, $dbname = self::DB_NAME)
	{
		$name = sha1($host . $user . $pass . $dbname);
		if (!isset(self::$instances[$name]))
			self::$instances[$name] = new self($host, $user, $pass, $dbname);
		return self::$instances[$name];
	}

	private function				_bindValues()
	{
		$count = count($this->_arguments);
		$total = $this->_nbArgsNamed + $this->_nbArgsUndefined;
		if ($count !== $total)
			throw new \RuntimeException(sprintf('Invalid number of arguments "%d" in total "%d" while parsing SQL request', $count, $total));
		$i = 0;
		$query = $this->_query;
		foreach ($this->_arguments as $k => $v)
		{
			if (is_array($v))
			{
				foreach ($v as $kv => $vv)
				{
					if (is_string($vv) && !is_numeric($vv))
						$v[$kv] = "'$vv'";
					else if ($vv === null)
						$v[$kv] = 'NULL';
					else if (!is_numeric($vv))
						throw new \InvalidArgumentException('In array values must be string, null or numeric');
				}
				$v = '(' . implode(', ', $v) . ')';
			}
			else if (is_string($v) && !is_numeric($v))
				$v = "'$v'";
			else if ($v === null)
				$v = 'NULL';
			else if (!is_numeric($v))
				throw new \InvalidArgumentException('Values must be array, null, string or numeric');
			if (is_int($k))
			{
				$i = strpos($query, '?', $i);
				$query = substr($query, 0, $i) . $v . substr($query, $i + 1);
				$i++;
			}
			else
			{
				$j = strpos($query, $k);
				$query = substr($query, 0, $j) . $v . substr($query, $j + strlen($k));
			}
		}
		$this->_query = $query;
	}

	private function				_parseArguments($request)
	{
		$parse = explode(' ', $request);
		foreach ($parse as $k => $elem)
		{
			if (($pos = strpos($elem, ':')) !== false)
			{
				if (preg_match('#[^A-Za-z0-9_]$#', $elem))
				{
					$value = substr($elem, $pos);
					while (preg_match('#[^A-Za-z0-9_]$#', $value, $matches))
						$value = substr($value, 0, strlen($value) - 1);
					$this->_argNames[] = $value;
				}
				else
					$this->_argNames[] = substr($elem, $pos);
				$this->_nbArgsNamed++;
			}
			else if ($elem === '?' || strpos($elem, '?') !== false)
				$this->_nbArgsUndefined++;
		}
	}

	private function				_reset()
	{
		$this->_nbArgsNamed = 0;
		$this->_nbArgsUndefined = 0;
		$this->_argNames = array();
		$this->_arguments = array();
		$this->_query = '';
	}

	private final function			__construct($host, $user, $pass, $dbname)
	{
		$this->_handler = mysqli_connect($host, $user, $pass, $dbname);
		$this->errno = mysqli_errno($this->_handler);
	}
}