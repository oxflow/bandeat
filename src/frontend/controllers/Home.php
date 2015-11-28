<?php

namespace Frontend\Controllers;
use Lib\Controller,
	Lib\Link,
	Lib\Route,
	Lib\App,
	Lib\Session,
	Lib\Request;

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

	public function		commanderAction()
	{
		$nom = Request::postData('nom');
		$nomResto = Request::postData('restaurant');
		$adresse = Request::postData('adresse');
		$email = Request::postData('email');
		$tel = Request::postData('tel');
		var_dump($nom, $nomResto, $adresse, $email, $tel);
	}
}
