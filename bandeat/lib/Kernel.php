<?php

namespace Lib;

require_once 'Config.php';
require_once 'Autoloader.php';

use Lib\Config as C;

class							Kernel
{
	private						$bundles;
	private						$classes;
	private						$config;

	protected static			$instance = NULL;

	const						DEV_MODE = 1;
	const						PRO_MODE = 0;

	public static function		getInstance($env = self::DEV_MODE)
	{
		if ($env === self::DEV_MODE)
			error_reporting(E_ALL);
		else
			error_reporting(0);
		if (!isset(self::$instance))
			self::$instance = new self($env);
		return self::$instance;
	}

	private function			loadBundles()
	{
		$bundles = array(
			'Application' => new Autoloader('Application', APPLICATION),
			'Console' => new Autoloader('Console', CONSOLE)
		);
		$this->bundles = $bundles;
	}

	public function				getApp()
	{
		$app = C::get('DEFAULT_APP');
		if (array_key_exists($app, $this->bundles))
		{
			$class = $app . '\\' . $app . 'Application';
			return new $class($this);
		}
		throw new \RuntimeException(sprintf('L\'application "%s" n\'existe pas', $app));
	}

	public function				getConsole()
	{
		$class = 'Console\\Console';
		return new $class($this);
	}

	public function				getBundles()
	{
		return $this->bundles;
	}

	public function				getClasses()
	{
		return $this->classes;
	}

	protected static function	config()
	{
		C::add('BASEDIR', str_replace('\\', '/', dirname(__DIR__))); // Windows
		C::add('APP', BASEDIR . '/app');
		C::add('SRC', BASEDIR . '/src');
		C::add('WEB', BASEDIR . '/web');
		C::add('LIB', BASEDIR . '/lib');
		C::add('MANAGER', BASEDIR . '/models/managers');
		C::add('ENTITY', BASEDIR . '/models/entities');

		// PERSONNALISATION
		C::add('DEFAULT_APP', 'Application');
		C::add('APPLICATION', SRC . '/application');
		C::add('FRONTEND', SRC . '/frontend');
		C::add('BACKEND', SRC . '/backend');
		C::add('CONSOLE', SRC . '/Framework/console');
		C::add('SEL_SHA', 'frame3214work@tw0rk');
	}

	protected function			__construct()
	{
		self::config();
		$this->loadBundles();
		$classes = array();
		$classes[] = new Autoloader('Entity', ENTITY, '.entity.php');
		$classes[] = new Autoloader('Managers', MANAGER);
		$classes[] = new Autoloader('Lib', LIB);
		$this->classes = $classes;
		$this->_register();
	}

	private function			_register()
	{
		$classes = array_merge($this->bundles, $this->classes);
		foreach ($classes as $bundle)
			$bundle->register();
	}
}