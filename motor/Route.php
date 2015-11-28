<?php

namespace Lib;

class						Route
{
	private					$url;
	private					$controller;
	private					$action;
	private					$method;
	private					$vars = array();
	private					$varsNames = array();
	private					$varsTypes = array();
	private					$regex;

	public function			__construct($url = '', $controller = '', $action = 'index', $method = 'GET')
	{
		$this->setUrl($url);
		$this->setController($controller);
		$this->setAction($action);
		$this->setVarsNames();
		$this->setMethod($method);
	}

	public function			match($url)
	{
		if (preg_match('#^' . $this->regex . '/?$#', $url, $matches))
			return $matches;
		return false;
	}

	public function			hasVars()
	{
		return (!empty($this->vars));
	}

	public function			hasVarsNames()
	{
		return (!empty($this->varsNames));
	}

	public function			setVarsNames()
	{
		preg_match_all('#\[([ias]):([a-zA-Z_]+[a-zA-Z0-9_-]*)\]#', $this->url, $vars);
		if (empty($this->url) OR empty($vars))
			return $this;
		$regex = $this->url;
		$c = count($vars[0]);
		for ($i = 0; $i < $c; $i++)
		{
			$type = $vars[1][$i];
			$name = $vars[2][$i];
			if ($type !== 'a' AND $type !== 'i' AND $type !== 's')
				throw new \LogicException(sprintf('Type "%s" does not exists', $type));
			$replace = '[' . $type . ':' . $name . ']';
			switch ($type)
			{
				case 'a':
					$regex = str_replace($replace, '([a-zA-Z0-9_]+)', $regex);
					break;
				case 's':
					$regex = str_replace($replace, '([a-zA-Z_]+)', $regex);
					break;
				case 'i':
					$regex = str_replace($replace, '([0-9]+)', $regex);
					break;
			}
			if (!in_array($name, $this->varsNames))
			{
				$this->varsNames[$i] = $name;
				$this->varsTypes[$i] = $type;
			}
			else
				throw new \LogicException(sprintf('Varname "%s" already exists in Route "%s"', $name, $this->url));
		}
		$this->setRegex($regex);
		return $this;
	}

	public function			setController($controller)
	{
		if (is_string($controller))
			$this->controller = $controller;
		return $this;
	}

	public function			setAction($action)
	{
		if (is_string($action))
			$this->action = $action;
		return $this;
	}

	public function			setMethod($method)
	{
		if (is_string($method))
			$this->method = $method;
		else if (is_array($method))
			$this->method = $method;
		return $this;
	}

	public function			setRegex($regex)
	{
		if (is_string($regex))
			$this->regex = str_replace('.', '\\.', $regex);
		return $this;
	}

	public function			setUrl($url)
	{
		if (is_string($url))
			$this->url = $url;
		return $this;
	}

	public function			setVars(array $vars)
	{
		$this->vars = $vars;
		return $this;
	}

	public function			setVarsTypes($varsTypes)
	{
		if (is_array($varsTypes) AND count($varsTypes) === count($this->varsNames))
		{
			foreach ($varsTypes as $key => $val)
			{
				if (in_array($key, $this->varsNames))
					$this->varsTypes[$key] = $val;
				else
					throw new \LogicException(sprintf('Variable "%s" doesn\'t exists'));
			}
		}
		return $this;
	}

	public function			url()
	{
		return $this->url;
	}

	public function			action()
	{
		return $this->action;
	}

	public function			controller()
	{
		return $this->controller;
	}

	public function			regex()
	{
		return $this->regex;
	}

	public function			method()
	{
		return $this->method;
	}

	public function			vars()
	{
		return $this->vars;
	}

	public function			varsNames()
	{
		return $this->varsNames;
	}

	public function			varsTypes()
	{
		return $this->varsTypes;
	}
}