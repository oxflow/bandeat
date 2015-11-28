<?php

namespace Lib;

use Lib\Http as H,
	Lib\Router as R,
	Lib\DAO as D;

abstract class			Controller extends Component
{
	protected			$action;
	protected			$controller;
	protected			$manager;
	protected			$view;
	protected			$request;
	protected			$page;
	protected			$link;
	protected			$vars = array();

	public function		__construct(App $app, $controller, $action, $vars)
	{
		parent::__construct($app);

		$db = D::getInstance();
		$db->prepare('SELECT * FROM comments WHERE ID IN :ids');
		$db->bind(':ids', array(3, null, 2), D::VALUE_TAB);
		$values = $db->select();
		die(var_dump($values, $db));

		$this->page = new Page();
		$this->manager = new ORM\Manager();
		$this->page->setLayout(APP . '/templates/base.php');
		$this->setAction($action);
		$this->setController($controller);
		$this->setVars($vars);
		$this->setView($action);
		$this->request = H::getVars();

		// STYLES
		$this->page->addVar('layout_jquery', R::getTemplate('js/jquery.min.js'));
		$this->page->addVar('layout_bootstrap_js', R::getTemplate('js/bootstrap.min.js'));
		$this->page->addVar('layout_style', R::getTemplate('css/style.css'));
		$this->page->addVar('layout_bootstrap_css', R::getTemplate('css/bootstrap.min.css'));

		// LINKS
		$this->page->addVar('layout_home', R::getUrl('Home', 'index'));
		$this->page->addVar('layout_login', R::getUrl('Home', 'login'));
		$this->page->addVar('layout_logout', R::getUrl('Home', 'logout'));
		$this->page->addVar('layout_cr_article', R::getUrl('Blog', 'create'));
		$this->page->addVar('layout_settings', R::getUrl('Home', 'settings'));
		$this->page->addVar('layout_m_users', R::getUrl('Admin', 'users'));

		//die(var_dump($this->page, H::hasCookie('PHPSESSID')));
	}

	public function		execute()
	{
		$action = $this->action . 'Action';
		if (!is_callable(array($this, $action)))
			H::redirect404();
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
		$layout = SRC . '/' . $this->app->name() . '/views/' . strtolower($this->controller) . '/' . $view . '.php';
		$this->page->setView($layout);
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