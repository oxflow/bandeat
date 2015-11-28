<?php

namespace Lib;

abstract class			Manager
{
	protected			$dao;
	protected			$entity;
	protected			$table;

	public function		__construct(\PDO $dao)
	{
		$this->dao = $dao;
	}

	protected function	insert(array $values)
	{
		$i = 0;
		$sql = 'INSERT INTO ' . $this->table . ' ';
		$part1 = '(';
		$part2 = '(';
		foreach ($values as $key => $value)
		{
			if (!is_string($key))
				throw new \InvalidArgumentException(sprintf('Invalid key "%s" must be a string', $key));
			if ($i > 0)
			{
				$part1 .= ', ';
				$part2 .= ', ';
			}
			$part1 .= $key;
			$part2 .= ":$key";
			$i++;
		}
		$part1 .= ') VALUES ' . $part2 . ')';
		$sql .= $part1;
		$req = $this->dao->prepare($sql) or die (var_dump($this->dao->errorInfo()));
		foreach ($values as $key => $value)
		{
			$name = ':' . $key;
			if (is_string($value))
				$req->bindValue($name, $value, \PDO::PARAM_STR);
			else if (is_numeric($value))
				$req->bindValue($name, $value, \PDO::PARAM_INT);
			else
				throw new \InvalidArgumentException(sprintf('Unknow type for "%s" value', $value));
		}
		$req->execute();
		$req->closeCursor();
		return true;
	}

	protected function	select($where = 0, $limit = 0, $order = NULL)
	{
		$sql = 'SELECT * FROM ' . $this->table;
		if (is_array($where) AND count($where) > 1)
		{
			$name = array_shift($where);
			$value = array_shift($where);
			if (!is_string($name))
				throw new \InvalidArgumentException(sprintf('Column name "%s" must be a string', $name));
			$sql .= ' WHERE ' . $name . ' = :' . $name;
		}
		else if (is_numeric($where) AND $where > 0)
			$sql .= ' WHERE ID = :id';
		if ($order != NULL)
			$sql .= ' ORDER BY ' . $order;
		if ($limit > 0)
			$sql .= ' LIMIT 0, ' . $limit;
		$req = $this->dao->prepare($sql) or die (var_dump($this->dao->errorInfo()));
		if (isset($name) AND is_string($value))
			$req->bindValue(':' . $name, $value, \PDO::PARAM_STR);
		else if (isset($name) AND is_numeric($value))
			$req->bindValue(':' . $name, $value, \PDO::PARAM_INT);
		else if (is_numeric($where) AND $where > 0)
			$req->bindValue(':id', $where, \PDO::PARAM_INT);
		$req->execute();
		$req->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, $this->entity);
		$list = $req->fetchAll();
		$req->closeCursor();
		return $list;
	}

	protected function	update($data)
	{
		if (!is_object($data) OR $data->isNew() OR get_class($data) !== $this->entity)
			return false;
		$sql = 'UPDATE ' . $this->table . ' SET ';
		$vars = $data->getData();
		$i = 0;
		foreach ($vars as $key => $value)
		{
			if (strtoupper($key) === "ID")
				continue ;
			if (!is_string($value) AND !is_numeric($value))
				throw new \InvalidArgumentException(sprintf('Unknow type for "%s" value', $value));
			if ($i > 0)
				$sql .= ', ';
			$sql .= $key . ' = :' . $key;
			$i++;
		}
		$sql .= ' WHERE ID = :id';
		$req = $this->dao->prepare($sql) or die (var_dump($this->dao->errorInfo()));
		$req->bindValue(':id', $data->id(), \PDO::PARAM_INT);
		foreach ($vars as $key => $value)
		{
			if (is_string($value))
				$req->bindValue(':' . $key, $value, \PDO::PARAM_STR);
			else
				$req->bindValue(':' . $key, $value, \PDO::PARAM_INT);
		}
		$req->execute();
		$req->closeCursor();
		return true;
	}

	protected function	updateArray(array $data)
	{
		if (!isset($data['id']))
			return false;
		$sql = 'UPDATE ' . $this->table . ' SET ';
		$i = 0;
		foreach ($data as $key => $value)
		{
			if (strtoupper($key) === "ID")
				continue ;
			if (!is_string($value) AND !is_numeric($value))
				throw new \InvalidArgumentException(sprintf('Unknow type for "%s" value', $value));
			if ($i > 0)
				$sql .= ', ';
			$sql .= $key . ' = :' . $key;
			$i++;
		}
		$sql .= ', timestampUpdate = null';
		$sql .= ' WHERE ID = :id';
		$req = $this->dao->prepare($sql) or die (var_dump($this->dao->errorInfo()));
		$req->bindValue(':id', $data['id'], \PDO::PARAM_INT);
		foreach ($data as $key => $value)
		{
			if (strtoupper($key) === "ID")
				continue ;
			if (is_string($value))
				$req->bindValue(':' . $key, $value, \PDO::PARAM_STR);
			else
				$req->bindValue(':' . $key, $value, \PDO::PARAM_INT);
		}
		$req->execute();
		$req->closeCursor();
		return true;
	}

	protected function	delete($data)
	{
		if (!is_object($data) OR $data->isNew() OR get_class($data) !== $this->entity)
			return false;
		$sql = 'DELETE FROM ' . $this->table . ' WHERE ID = :id';
		$req = $this->dao->prepare($sql) or die (var_dump($this->dao->errorInfo()));
		$req->bindValue(':id', $data->id(), \PDO::PARAM_INT);
		$req->execute();
		$req->closeCursor();
		return true;
	}
}