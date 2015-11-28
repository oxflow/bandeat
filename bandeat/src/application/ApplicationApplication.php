<?php

namespace Application;
use	Lib\App,
	Lib\Router as R,
	Lib\Http as H,
	Lib\Kernel;

class					ApplicationApplication extends App
{
	public function		__construct(Kernel $kernel)
	{
		$this->name = 'application';
		parent::__construct($kernel);
		R::load($this->name);
	}

	public function		run()
	{
		$controller = $this->getController();
		$controller->execute();
		H::$page = $controller->page();
		H::send();
	}
}