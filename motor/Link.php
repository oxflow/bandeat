<?php

namespace Lib;

class					Link extends Component
{
	private				$routes = array();
	private				$basepath = '/';

	public function		setBasePath($basepath)
	{
		if (is_string($basepath) AND $basepath[0] === '/')
			$this->basepath = $basepath;
		return $this;
	}

	public function		setRoutes($app, $basepath = '/')
	{
		$filename = SRC . DS . $app . DS . 'config' . DS . 'routes.xml';
		if (is_string($app) AND file_exists($filename))
		{
			$xml = new \DOMDocument();
			$xml->load($filename);
			$document = $xml->getElementsByTagName('route');
			foreach ($document as $route)
			{
				if (!empty($basepath))
					$url = $basepath . $route->getAttribute('url');
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
				$this->routes[] = $route;
			}
			return $this;
		}
		throw new \RuntimeException(sprintf('Routes not found in file "%s"', $filename));
	}

	public function		getUrl($controller, $action, $vars = array())
	{
		$matched_route = false;
		$route = new Route('', $controller, $action);
		$route->setVars($vars);
		foreach ($this->routes as $path)
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
			return NULL;
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

	public function		getTemplate($file, $web = true)
	{
		if ($web === true)
			return $this->basepath . 'web/' . $file;
		return $this->basepath . 'src/' . $this->app->name() . '/resources/' . $file;
	}
}