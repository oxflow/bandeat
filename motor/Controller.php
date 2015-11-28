<?php

namespace Lib;

abstract class			Controller extends Component
{
	protected			$action;
	protected			$controller;
	protected			$managers;
	protected			$view;
	protected			$request;
	protected			$page;
	protected			$link;
	protected			$vars = array();

	public function		__construct(App $app, $controller, $action, $vars)
	{
		parent::__construct($app);
		$this->page = new Page();
		$this->page->setLayout(APP . DS . 'templates' . DS . 'base.php');
		$this->setAction($action);
		$this->setController($controller);
		$this->setVars($vars);
		$this->setView($action);
		$this->link = $this->app->link();

		// STYLES
		$this->page->addVar('layout_jquery', $this->link->getTemplate('js/jquery.min.js'));
		$this->page->addVar('layout_bootstrap_js', $this->link->getTemplate('js/bootstrap.min.js'));
		$this->page->addVar('layout_style', $this->link->getTemplate('css/style.css'));
		$this->page->addVar('layout_bootstrap_css', $this->link->getTemplate('css/bootstrap.min.css'));

		// LINKS
		$this->page->addVar('layout_home', $this->link->getUrl('Home', 'index'));
		$this->page->addVar('layout_login', $this->link->getUrl('Home', 'login'));
		$this->page->addVar('layout_logout', $this->link->getUrl('Home', 'logout'));
		$this->page->addVar('layout_cr_article', $this->link->getUrl('Blog', 'create'));
		$this->page->addVar('layout_settings', $this->link->getUrl('Home', 'settings'));
		$this->page->addVar('layout_m_users', $this->link->getUrl('Admin', 'users'));

		try
		{
			$this->managers = new ManagerFactory();
		}
		catch (\RuntimeException $e)
		{
			var_dump($e->getMessage());
		}
		$this->request = $this->app->request()->method();
		if (Session::get('auth') === false)
		{
			$userManager = $this->managers->getManager('User');
			$login = $this->app->request()->cookieData('remember');
			if ($login !== NULL AND ($user = $userManager->getUser($login)) !== false)
			{
				$pass = sha1($user->password());
				$admin = $user->admin();
				$vars = array('auth' => true, 'login' => $login, 'pass' => $pass, 'login_time' => time(), 'admin' => $admin, 'id' => $user->id());
				Session::set($vars);
			}
		}
	}

	public function		execute()
	{
		$action = $this->action . 'Action';
		if (!is_callable(array($this, $action)))
			$this->app->response()->redirect404();
		call_user_func_array(array($this, $action), $this->vars);
	}

	public function		setAction($action)
	{
		if (is_string($action))
			$this->action = $action;
		return $this;
	}

	public function		setController($controller)
	{
		if (is_string($controller))
			$this->controller = $controller;
		return $this;
	}

	public function		setView($view)
	{
		if (!is_string($view))
			throw new \InvalidArgumentException('View must be a string');
		$this->view = $view;
		{
			$layout = SRC . DS . $this->app->name() . DS . 'views' . DS . $this->controller . DS . $view . '.php';
			$this->page->setView($layout);
		}
	}

	public function		setVars($vars)
	{
		if (is_array($vars))
			$this->vars = $vars;
		return $this;
	}

	public function		page()
	{
		return $this->page;
	}
}