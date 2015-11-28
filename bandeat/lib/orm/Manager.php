<?php

namespace Lib\ORM;

class					Manager
{
	protected			$entity;
	protected			$orm;

	public function		__construct($entity = NULL, $table = NULL, $reference = NULL)
	{
		$orm = ORM::getInstance();
		$this->setEntity($entity);
	}

	public function		create($values)
	{
		if (empty($this->table) || empty($this->primary_key))
			throw new \RuntimeException('Table or primary key must be non empty string');
	}

	public function		read($columns, $where = NULL, $order = NULL, $limit = NULL, $index = NULL)
	{
		if (empty($entity))
			throw new \RuntimeException('Entity must be not null');
		$columns_str = $this->_constructColumns($columns);
		$where_str = $this->_constructWhere($where, $vals);
		$order_str = $this->_constructOrderBy($order);
		$sql = 'SELECT ' . $columns_str . ' FROM ' . $this->table;
		if ($where_str !== false)
			$sql .= ' WHERE ' . $where_str;
		if ($order_str !== false)
			$sql .= ' ORDER BY ' . $order_str;
		if (is_int($limit) && $limit > 0)
		{
			if (is_int($index))
				$sql .= ' LIMIT ' . $index * $limit . ', ' . $limit;
			else
				$sql .= ' LIMIT ' . $limit;
		}
		$ret = array();
		$tab = $this->orm->db_select($sql, $vals);
		foreach ($tab as $kv => $vv)
		{
			foreach ($vv as $kvv => $vvv)
			{
				if (is_string($kvv) && in_array($kvv, $columns))
					$ret[$kv][$kvv] = $vvv;
			}
		}
		return $ret;
	}

	public function		search($where = NULL, $order = NULL, $limit = NULL, $index = NULL)
	{
		if (empty($this->table) || empty($this->primary_key))
			throw new \RuntimeException('Table or primary key must be non empty string');
		$where_str = $this->_constructWhere($where, $vals);
		$order_str = $this->_constructOrderBy($order);
		$sql = 'SELECT ' . $this->primary_key . ' FROM ' . $this->table;
		if ($where_str !== false)
			$sql .= ' WHERE ' . $where_str;
		if ($order_str !== false)
			$sql .= ' ORDER BY ' . $order_str;
		if (is_int($limit) && $limit > 0)
		{
			if (is_int($index))
				$sql .= ' LIMIT ' . $index * $limit . ', ' . $limit;
			else
				$sql .= ' LIMIT ' . $limit;
		}
		$ret = array();
		$tab_ids = $this->orm->db_select($sql, $vals);
		foreach ($tab_ids as $val)
			$ret[] = intval($val[$this->primary_key]);
		return $ret;
	}

	public function		getEntity()
	{
		return $this->entity;
	}

	public function		setEntity($entity)
	{
		if (is_object($entity))
		{
			$this->entity = $entity;
		}
		return $this;
	}

	private function	_constructWhere($where, &$values)
	{
		$values = array();
		if ($where === NULL)
		{
			return false;
		}
		else if ((!is_array($where) && !is_string($where)) || empty($where))
		{
			throw new \LogicException('Parameter "where" must be non empty string or array');
		}
		if (is_array($where))
		{
			$first = false;
			$where_str = '';
			foreach ($where as $kw => $vw)
			{
				if ($first)
				{
					$where_str .= ' AND ';
				}
				if (is_string($vw))
				{
					$where_str .= $vw;
				}
				else if (is_array($vw))
				{
					$len = count($vw);
					if ($len < 2 || $len > 3)
					{
						throw new \LogicException('"where" array must have 2 or 3 values');
					}
					else if ($len === 2)
					{
						$cond = strtoupper($vw[1]);
						if ($cond === 'IS NULL' || $cond === 'IS NOT NULL')
						{
							$where_str .= $vw[0] . ' ' . $cond;
						}
						else
						{
							throw new \LogicException('"where" condition have wrong value');
						}
					}
					else
					{
						$cond = strtoupper($vw[1]);
						if ($cond === '=' || $cond === '!=' || $cond === '<>' || $cond === '>' || $cond === '<')
						{
							$where_str .= $vw[0] . ' ' . $cond . ' ';
							if (is_array($vw[2]) && count($vw[2]) === 1)
							{
								$key = key($vw[2]);
								$val = current($vw[2]);
								if (is_int($key))
								{
									$where_str .= '?';
									$values[] = $val;
								}
								else
								{
									$where_str .= ':' . $key;
									$values[':' . $key] = $val;
								}
							}
							else
							{
								$where_str .= '?';
								$values[] = $vw[2];
							}
						}
						else if (($cond === 'IN' || $cond === 'NOT IN') && is_array($vw[2]))
						{
							$b = false;
							$where_str .= $vw[0] . ' ' . $cond . ' (';
							foreach ($vw[2] as $kv => $vv)
							{
								if ($b)
								{
									$where_str .= ', ';
								}
								if (is_int($kv))
								{
									$where_str .= '?';
									$values[] = $vv;
								}
								else
								{
									$where_str .= ':' . $kv;
									$values[$kv] = $vv;
								}
								if (!$b)
								{
									$b = true;
								}
							}
							$where_str .= ')';
						}
						else
						{
							throw new \LogicException('"where" condition have wrong value');
						}
					}
				}
				if (!$first)
				{
					$first = true;
				}
			}
		}
		else
		{
			$where_str = $where;
		}
		return $where_str;
	}

	private function	_constructColumns($columns)
	{
		if ($columns === NULL)
		{
			return '*';
		}
		else if ((!is_array($columns) && !is_string($columns)) || empty($columns))
		{
			throw new \LogicException('Parameter "columns" must be non empty string or array');
		}
		if (is_array($columns))
		{
			return implode(', ', $columns);
		}
		else if (is_string($columns))
		{
			return $columns;
		}
	}

	private function	_constructOrderBy($order)
	{
		if ($order === NULL)
		{
			return false;
		}
		else if ((!is_array($order) && !is_string($order)) || empty($order))
		{
			throw new \LogicException('Parameter "columns" must be non empty string or array');
		}
		if (is_array($order))
		{
			return implode(', ', $order);
		}
		else if (is_string($order))
		{
			return $order;
		}
	}
}