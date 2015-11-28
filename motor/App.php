<?php

namespace Lib;

abstract class			App
{
	protected			$name = '';
	protected			$request;
	protected			$link;
	protected			$response;
	protected			$router;

	public function		     	__construct()
	{
		date_default_timezone_set('Europe/Paris');
		$this->request = new Request();
		$this->response = new Response($this);
		$this->router = new Router($this);
		$this->link = new Link($this);
		$this->link->setBasePath('/');
		$this->link->setRoutes('frontend', '/');
		$this->link->setRoutes('backend', '/admin/');
		Session::init();
	}

	public function				getController()
	{
		try
		{
			$route = $this->router->find($this->request()->uri());
		}
		catch (\RuntimeException $e)
		{
			if ($e->getCode() === Router::NO_ROUTE)
				$this->response->redirect404();
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

	public function				link()
	{
		return $this->link;
	}

	public function				request()
	{
		return $this->request;
	}

	public function				response()
	{
		return $this->response;
	}

	public function				router()
	{
		return $this->router;
	}
}