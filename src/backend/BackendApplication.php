<?php

namespace Backend;
use	Lib\App;

class					BackendApplication extends App
{
	public function		__construct()
	{
		$this->name = 'backend';
		parent::__construct();
		$this->router->setBasePath('/admin/')->loadXML();
	}

	public function		run()
	{
		$controller = $this->getController();
		$controller->execute();
		$this->response->setPage($controller->page());
		$this->response->send();
	}
}