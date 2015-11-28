<?php

namespace Application\Controllers;
use Lib\Controller,
	Lib\Link,
	Lib\Route,
	Lib\App,
	Lib\Session as S,
	Lib\Router as R,
	Lib\Http as H;

class					Main extends Controller
{
	public function		__construct(App $app, $controller, $action, $vars)
	{
		parent::__construct($app, $controller, $action, $vars);
		$this->page->addVar('request', $this->request);
		R::load('application');
	}

	public function		indexAction()
	{
		$this->page->addVar('layout_title', 'Home');
		//$list = $this->manager->read(NULL, NULL, 'timestamp DESC', 10);
		//$this->page->addVar('articles', $list);
		/* OLD SOURCE
		$manager = $this->managers->getManager('Article');
		$this->page->addVar('articles', $manager->getList());
		$this->page->addVar('userManager', $this->managers->getManager('User'));*/
		$this->page->addVar('link', $this->link);
	}
}