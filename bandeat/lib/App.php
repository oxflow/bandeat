<?php

namespace Lib;

use Lib\Session as S,
	Lib\Router as R,
	Lib\Http as H;

abstract class			App
{
	public				$request;
	protected			$name = '';
	protected			$kernel;

	public function				__construct(Kernel $kernel)
	{
		date_default_timezone_set('Europe/Paris');
		S::init();
		R::$basepath = '/Framework1/';
		$this->kernel = $kernel;
		$this->request = H::getVars();
	}

	public function				getController()
	{
		try
		{
			$route = R::find($this->request['uri']);
		}
		catch (\RuntimeException $e)
		{
			if ($e->getCode() === R::NO_ROUTE)
				H::redirect404();
		}
		$controller = $route->controller();
		$action = $route->action();
		$class = ucfirst($this->name) . '\\Controllers\\' . $controller;
		$vars = $route->vars();
		$_GET = array_merge($_GET, $vars);
		return new $class($this, $controller, $action, $vars);
	}

	abstract public function	run();

	public function				name()
	{
		return $this->name;
	}
}