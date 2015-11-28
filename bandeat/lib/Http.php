<?php

namespace Lib;

use Lib\Session as S,
	Lib\Router as R;

class							Http
{
	const						RESTRAINT_MODE = true;

	public static				$globals = null;
	public static				$page = null;

	public static function		header($header)
	{
		if (!is_string($header))
			throw new \LogicException("Header must be a string");
		header($header);
	}

	public static function		getVars($refresh = false, $restraint = self::RESTRAINT_MODE, $secure = true)
	{
		if (!isset(self::$globals) || $refresh === true)
		{
			self::$globals = array(
				'uri' => $_SERVER['REQUEST_URI'],
				'redirect' => $_SERVER['REDIRECT_URL'],
				'agent' => $_SERVER['HTTP_USER_AGENT'],
				'IP' => $_SERVER['REMOTE_ADDR'],
				'lang' => $_SERVER['HTTP_ACCEPT_LANGUAGE'],
				'mail' => $_SERVER['SERVER_ADMIN'],
				'method' => $_SERVER['REQUEST_METHOD'],
				'port' => (int) $_SERVER['SERVER_PORT'],
				'protocol' => $_SERVER['SERVER_PROTOCOL'],
				'phpsessid' => isset($_COOKIE['PHPSESSID']) ? $_COOKIE['PHPSESSID'] : '',
				'script' => $_SERVER['SCRIPT_NAME'],
				'statut' => (int) $_SERVER['REDIRECT_STATUS'],
				'cookie' => $_COOKIE,
				'env' => $_ENV,
				'file' => $_FILES,
				'get' => $_GET,
				'post' => $_POST
			);
			if ($restraint === false)
				self::$globals['server'] = $_SERVER;
			if ($secure === true && (!empty(self::$globals['get']) || !empty(self::$globals['post'])))
			{
				foreach (self::$globals['get'] as $k => $v)
					self::$globals['get'][$k] = htmlspecialchars($v, ENT_QUOTES);
				foreach (self::$globals['post'] as $k => $v)
					self::$globals['post'][$k] = htmlspecialchars($v, ENT_QUOTES);
			}
			if (S::$started === true)
				self::$globals['session'] = $_SESSION;
			unset(self::$globals['cookie']['PHPSESSID']);
			ksort(self::$globals);
		}
		return self::$globals;
	}

	public static function		method()
	{
		if (empty(self::$globals['method']) && empty(self::$globals['server']['REQUEST_METHOD']))
		{
			self::$globals['server']['REQUEST_METHOD'] = 'GET';
			self::$globals['method'] = self::$globals['server']['REQUEST_METHOD'];
		}
		return self::$globals['method'];
	}

	public static function		redirect404()
	{
		self::$page = new Page();
		self::$page->setLayout(APP . '/templates/base.php');
		self::$page->setView(APP . '/templates/error.php');

		// STYLES
		self::$page->addVar('layout_jquery', R::getTemplate('js/jquery.min.js'));
		self::$page->addVar('layout_bootstrap_js', R::getTemplate('js/bootstrap.min.js'));
		self::$page->addVar('layout_style', R::getTemplate('css/style.css'));
		self::$page->addVar('layout_bootstrap_css', R::getTemplate('css/bootstrap.min.css'));

		// LINKS
		self::$page->addVar('layout_home', R::getUrl('Home', 'index'));
		self::$page->addVar('layout_login', R::getUrl('Home', 'login'));
		self::$page->addVar('layout_logout', R::getUrl('Home', 'logout'));
		self::$page->addVar('layout_cr_article', R::getUrl('Blog', 'create'));
		self::$page->addVar('layout_settings', R::getUrl('Home', 'settings'));
		self::$page->addVar('layout_m_users', R::getUrl('Admin', 'users'));
		self::send(self::$page);
	}

	public static function		redirect($location)
	{
		if (!is_string($location))
			throw new \RuntimeException("Redirection must be a string");
		header('Location: ' . $location);
		exit ;
	}

	public static function		send($page = null)
	{
		if ((!isset(self::$page) || !self::$page instanceof Page) && (!isset($page) || !$page instanceof Page))
			throw new \RuntimeException('Variable "$page" must be defined as PageInterface before sending response');
		else if (!isset($page) || !$page instanceof PageInterface)
			$page = self::$page;
		exit ($page->render());
	}

	public static function		sendCookie($name, $value, $expire = false, $path = '', $domain = '', $httpOnly = true)
	{
		if ($expire === false OR !is_int($expire))
			$expire = 0;
		setcookie($name, $value, $expire, $path, $domain, false, $httpOnly);
		self::$globals['cookie'][$name] = $value;
	}

	public static function		unsetCookie($name)
	{
		setcookie($name, null, -1);
		unset($_COOKIE[$name], self::$globals['cookie'][$name]);
	}

	public static function		__callStatic($name, array $arguments)
	{
		$valid_functions = array('cookie', 'env', 'file', 'get', 'post', 'server');
		if ((!is_string($arguments[0]) && !is_int($arguments[0])) || empty($arguments[0]))
			throw new \LogicException(sprintf('Argument "$key" isn\'t valid for H::%s', $name));
		foreach ($valid_functions as $function)
		{
			if ($name === $function)
				return isset(self::$globals[$function][$arguments[0]]) ? self::$globals[$function][$arguments[0]] : null;
			else if ('has' . ucfirst($function) === $name)
				return isset(self::$globals[$function][$arguments[0]]);
		}
		throw new \RuntimeException(sprintf('Function "%s" doesn\'t exists', $name));
	}

	private final function		__construct()
	{
	}
}