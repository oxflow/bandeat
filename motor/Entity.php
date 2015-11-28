<?php

namespace Lib;

abstract class			Entity
{
	protected			$data = array();
	protected			$ID = NULL;

	public function		__construct($data = NULL)
	{
		if (is_array($data) AND !empty($data))
			$this->hydrate($data);
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
		if (!empty($this->data))
			return $this->data;
		$methods = get_class_methods($this);
		foreach ($methods as $name)
		{
			$attr = strtolower(substr($name, 3));
			if ($attr !== "id" AND substr($name, 0, 3) === "set" AND in_array($attr, $methods))
				$this->data[$attr] = $this->$attr();
		}
		return $this->data;
	}
}