<?php

namespace Lib;

class						Router extends Component
{
	const					NO_ROUTE = 1;

	private					$routes = array();
	private					$basePath = '';

	public function			addRoute(Route $route)
	{
		if (!in_array($route, $this->routes))
			$this->routes[] = $route;
	}

	public function			loadXML()
	{
		$xml = new \DOMDocument();
		$xml->load(SRC . DS . $this->app->name() . DS . 'config' . DS . 'routes.xml');
		$document = $xml->getElementsByTagName('route');
		foreach ($document as $route)
		{
			if (!empty($this->basePath))
				$url = $this->basePath . $route->getAttribute('url');
			else
				$url = $route->getAttribute('url');
			$controller = $route->getAttribute('controller');
			$action = $route->getAttribute('action');
			$method = $route->getAttribute('method');
			$tab = explode('/', $method);
			if (count($tab) > 1)
				$method = $tab;
			if (!empty($method))
				$route = new Route($url, $controller, $action, $method);
			else
				$route = new Route($url, $controller, $action);
			$this->addRoute($route);
		}
	}

	public function			find($url)
	{
		$method = $this->app->request()->method();
		foreach ($this->routes as $route)
		{
			$routeMethod = $route->method();
			$matched_method = false;
			if (is_array($routeMethod))
			{
				foreach ($routeMethod as $value)
				{
					if ($value === $method)
					{
						$matched_method = true;
						continue ;
					}
				}
			}
			else if ($routeMethod === $method)
				$matched_method = true;
			if (($values = $route->match($url)) !== false AND $matched_method)
			{
				if ($route->hasVarsNames())
				{
					$names = $route->varsNames();
					$vars = array();
					foreach ($values as $key => $match)
					{
						if ($key !== 0)
							$vars[$names[$key - 1]] = $match;
					}
					$route->setVars($vars);
				}
				return $route;
			}
		}
		throw new \RuntimeException(sprintf("Aucune route ne correspond a l'url '%s'", $url), self::NO_ROUTE);
	}

	public function			setBasePath($basePath)
	{
		if (is_string($basePath))
			$this->basePath = $basePath;
		return $this;
	}

	public function			setRoutes(array $routes)
	{
		foreach ($routes as $route)
			if ($route Instanceof Route AND !in_array($route, $this->routes))
				$this->routes[] = $route;
		return $this;
	}

	public function			basePath()
	{
		return $this->basePath;
	}

	public function			routes()
	{
		return $this->routes;
	}
}