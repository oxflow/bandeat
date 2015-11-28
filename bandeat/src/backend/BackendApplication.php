<?php

namespace Backend;
use	Lib\App;

class					BackendApplication extends App
{
	public function		__construct(\Lib\Kernel $kernel)
	{
		$this->name = 'backend';
		parent::__construct($kernel);
		$this->router->setBasePath('/Framework1/admin/')->loadXML();
	}

	public function		run()
	{
		$controller = $this->getController();
		$controller->execute();
		$this->response->setPage($controller->page());
		$this->response->send();
	}
}