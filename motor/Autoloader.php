<?php

class					Autoloader
{
	const				D_EXT = '.php';
	const				D_SEP = '\\';

	private				$namespace = NULL;
	private				$includePath = NULL;
	private				$separator = self::D_SEP;
	private				$extension = self::D_EXT;

	public function		__construct($namespace, $includePath, $extension = NULL, $separator = NULL)
	{
		$this->setNamespace($namespace);
		$this->setIncludePath($includePath);
		if ($separator !== NULL)
			$this->setSeparator($separator);
		if ($extension !== NULL)
			$this->setExtension($extension);
	}

	public function		register()
	{
		spl_autoload_register(array($this, 'loadClass'));
	}

	public function		unregister()
	{
		spl_autoload_unregister(array($this, 'loadClass'));
	}

	public function		loadClass($className)
	{
		if ($this->namespace !== NULL)
		{
			if (($namespace = $this->namespace.$this->separator) === substr($className, 0, strlen($namespace)))
				$className = substr($className, strlen($namespace));
			else
				unset($namespace);
		}
		if ($this->namespace === NULL || isset($namespace))
		{
			$fileName = '';
			$namespace = '';
			if (($lastNsPos = strripos($className, $this->separator)) !== false)
			{
				$namespace = strtolower(substr($className, 0, $lastNsPos));
				$className = substr($className, $lastNsPos + 1);
				$fileName = str_replace($this->separator, DS, $namespace) . DS;
			}
			$fileName .= str_replace('_', DS, $className) . $this->extension;
			$include = ($this->includePath !== NULL ? $this->includePath . DS : '') . $fileName;
			require_once $include;
		}
	}

	public function		setNamespace($namespace)
	{
		if (is_string($namespace))
			$this->namespace = $namespace;
	}

	public function		setIncludePath($includePath)
	{
		if (is_string($includePath) AND !empty($includePath))
			$this->includePath = $includePath;
	}

	public function		setSeparator($separator)
	{
		if (is_string($separator) AND !empty($separator))
			$this->separator = $separator;
	}

	public function		setExtension($extension)
	{
		if (is_string($extension) AND strlen($extension) > 1 AND $extension[0] === '.')
			$this->extension = $extension;
	}

	public function		getNamespace()
	{
		return $this->namespace;
	}

	public function		getIncludePath()
	{
		return $this->includePath;
	}

	public function		getSeparator()
	{
		return $this->separator;
	}

	public function		getExtension()
	{
		return $this->extension;
	}
}