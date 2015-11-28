<?php

namespace Lib\ORM;

abstract class			Entity
{
	protected			$data = array();
	protected			$fields;
	protected			$methods;
	protected			$reference;
	protected			$primary_key = 'ID';
	protected			$ID = NULL;

	public function		__construct($data = NULL)
	{
		if (is_array($data) AND !empty($data))
			$this->hydrate($data);
	}

	public function		__set($name, $value)
	{
		$method = 'set' . ucfirst($name);
		if (isset($this->$name) && is_callable($this, $method))
		{
			$this->$method($value);
			$this->data[$name] = $value;
		}
		else if (isset($this->name))
			throw new \RuntimeException(sprintf('Set method does\'t exists or is not callable for attribute "%s"', $name));
		else
			throw new \RuntimeException(sprintf('Attribute "%s" does not exists in class "%s"', $name, get_class($this)));
	}

	public function		isNew()
	{
		return (empty($this->ID));
	}

	public function		setId($id)
	{
		$this->ID = intval($id);
	}

	public function		id()
	{
		return $this->ID;
	}

	public function		hydrate(array $data)
	{
		$array = array();
		foreach ($data as $key => $value)
		{
			$method = 'set' . ucfirst($key);
			if (is_callable(array($this, $method)))
				$this->$method($value);
			$array[$key] = $value;
		}
		$this->data = $array;
	}

	public function		getData()
	{
		if (!isset($this->methods))
		{
			$methods = get_class_methods($this);
			foreach ($methods as $name)
			{
				$attr = strtolower(substr($name, 3));
				if ($attr !== "id" && substr($name, 0, 3) === "set" && in_array($attr, $methods))
				{
					$this->methods[] = $name;
					$this->data[$attr] = $this->$attr();
				}
			}
		}
		else
		{
			foreach ($this->methods as $name)
			{
				$attr = strtolower(substr($name, 3));
				$this->data[$attr] = $this->$attr();
			}
		}
		return $this->data;
	}

	public function		getMethods()
	{
		return $this->methods;
	}

	public function		setFields($fields)
	{
		if (is_array($fields))
		{
			foreach ($fields as $name => $value)
			{
			}
		}
	}
}