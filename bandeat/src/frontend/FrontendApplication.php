<?php

namespace Frontend;
use	Lib\App,
	Lib\R,
	Lib\H;

class					FrontendApplication extends App
{
	public function		__construct(\Lib\Kernel $kernel)
	{
		$this->name = 'frontend';
		parent::__construct($kernel);
		R::load($this->name);
		R::load('backend');
	}

	public function		run()
	{
		$controller = $this->getController();
		$controller->execute();
		H::$page = $controller->page();
		H::send();
	}
}