<?php

namespace Lib;

use Lib\Http as H;

class						Router
{
	const					NO_ROUTE = 1;

	public static			$app = null;
	public static			$routes = array();
	public static			$basepath = '/';

	public static function	addRoute(Route $route)
	{
		if (!in_array($route, self::$routes))
			self::$routes[] = $route;
	}

	public static function	load($app = null)
	{
		if ((!is_string($app) || empty($app)) && (!is_string(self::$app) || empty(self::$app)))
			throw new \InvalidArgumentException('$app or self must be defined as non empty string in Router::load()');
		if (empty($app) || !is_string($app))
			$app = self::$app;
		$filename = SRC . '/' . $app . '/config/routes.xml';
		if (file_exists($filename))
		{
			$xml = new \DOMDocument();
			$xml->load($filename);
			$document = $xml->getElementsByTagName('route');
			foreach ($document as $route)
			{
				if (is_string(self::$basepath) && !empty(self::$basepath))
					$url = self::$basepath . $route->getAttribute('url');
				else
					$url = $route->getAttribute('url');
				$name = $route->getAttribute('name');
				$controller = $route->getAttribute('controller');
				$action = $route->getAttribute('action');
				$method = $route->getAttribute('method');
				$tab = explode('/', $method);
				if (count($tab) > 1)
					$method = $tab;
				if (!empty($method))
					$route = new Route($name, $url, $controller, $action, $method);
				else
					$route = new Route($name, $url, $controller, $action);
				self::$routes[] = $route;
			}
			return true;
		}
		throw new \RuntimeException(sprintf('Routes not found in file "%s"', $filename));
	}

	public static function	find($url, $byname = false)
	{
		$method = H::method();
		foreach (self::$routes as $route)
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
			if (!$matched_method)
				break ;
			if ($byname === false)
			{
				if (($values = $route->match($url)) !== false)
				{
					if ($route->hasVarsNames())
					{
						$names = $route->varsNames();
						die(var_dump($values));
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
			else if (is_string($byname))
			{
				if (($values = $route->match($url)))
				{
				}
			}
			else
			{
				throw new \LogicException('Arg $byname must be false or non empty string in Router::find()');
			}
		}
		throw new \RuntimeException(sprintf("Aucune route ne correspond a l'url '%s'", $url), self::NO_ROUTE);
	}

	public static function	setBasepath($basepath)
	{
		if (!is_string($basepath) || empty($basepath))
			throw new \InvalidArgumentException('$basepath must be a non empty string in Router::setBasepath()');
		self::$basepath = $basepath;
	}

	public static function	setRoutes(array $routes)
	{
		foreach ($routes as $route)
			if ($route instanceof Route && !in_array($route, self::$routes))
				self::$routes[] = $route;
	}

	public static function	getUrl($controller, $action, $vars = array())
	{
		$matched_route = false;
		$route = new Route('', $controller, $action);
		$route->setVars($vars);
		foreach (self::$routes as $path)
		{
			if ($path->controller() != $route->controller())
				continue ;
			else if ($path->action() != $route->action())
				continue ;
			if ($path->hasVarsNames())
			{
				if (!$route->hasVars())
					continue ;
				$vars = $route->vars();
				$varsNames = $path->varsNames();
				foreach ($vars as $name => $value)
					if (!in_array($name, $varsNames))
						throw new \InvalidArgumentException(sprintf('Key "%s" doesn\'t exists for route "%s"', $name, $path->url()));
				$matched_route = $path;
				break ;
			}
			else if (!$route->hasVars())
			{
				$matched_route = $path;
				break ;
			}
		}
		if (!$matched_route)
			return null;
		$vars = $route->vars();
		$route = $matched_route;
		$url = $route->url();
		$names = $route->varsNames();
		$types = $route->varsTypes();
		foreach ($names as $k => $name)
		{
			if (!array_key_exists($name, $vars))
				throw new \InvalidArgumentException(sprintf('Key "%s" is not set for route "%s"', $name, $route->url()));
			$regex = '[' . $types[$k] . ':' . $name . ']';
			$url = str_replace($regex, $vars[$name], $url);
		}
		return $url;
	}

	public static function	getTemplate($file, $template = true, $app = null)
	{
		if (!is_string($file) || empty($file))
			throw new \InvalidArgumentException('$file must be a non empty string in Router::getTemplate()');
		if ($template === true)
			return self::$basepath . 'web/' . $file;
		if (!empty($app) && is_string($app))
			return self::$basepath . 'src/' . $app . '/resources/' . $file;
		else if (!empty(self::$app) && is_string(self::$app))
			return self::$basepath . 'src/' . self::$app . '/resources/' . $file;
		throw new \RuntimeException('$app must be defined as non empty string in Router::getTemplate on template mode');
	}

	private final function	__construct()
	{
	}
}