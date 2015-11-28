<?php

namespace Frontend;
use	Lib\App;

class					FrontendApplication extends App
{
	public function		__construct()
	{
		$this->name = 'frontend';
		parent::__construct();
		$this->router->setBasePath('/')->loadXML();
	}

	public function		run()
	{
		$controller = $this->getController();
		$controller->execute();
		$this->response->setPage($controller->page());
		$this->response->send();
	}
}