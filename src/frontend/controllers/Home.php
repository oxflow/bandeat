<?php

namespace Frontend\Controllers;
use Lib\Controller,
	Lib\Link,
	Lib\Route,
	Lib\App,
	Lib\Session;

class					Home extends Controller
{
	public function		__construct(App $app, $controller, $action, $vars)
	{
		parent::__construct($app, $controller, $action, $vars);
		$this->page->addVar('request', $this->request);
	}

	public function		indexAction()
	{
	}
}
