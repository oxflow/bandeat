<?php

namespace Console;

use Lib\App,
	Lib\Kernel;

class					Console extends App
{
	private				$commands;
	private				$command;

	private function	setCommands()
	{
		$commands = array(
			'generate:entity' => 'Entity',
			'generate:manager' => 'Manager'
		);
		$this->commands = $commands;
		return $commands;
	}

	public function		getBundlepath($name)
	{
		$bundles = $this->kernel->getBundles();
		$tab = explode('\\', $name);
		$ns = $tab[0];
		if (array_key_exists($ns, $bundles))
			return SRC . DS . strtolower($ns);
		else
			return false;
	}

	public function		loadCommand($name)
	{
		$nsp = 'Console\\Commands\\';
		if (!array_key_exists($name, $this->commands))
			return false;
		$class = $nsp . $this->commands[$name];
		if (file_exists(CONSOLE . DS . 'commands' . DS . $this->commands[$name] . '.php'))
		{
			return new $class($this, $name);
		}
		return false;
	}

	public function		run()
	{
		if ($this->command !== false)
		{
			$this->command->execute();
		}
		else
		{
			/*$page = new \Lib\Page;
			$page->setView(FRONTEND . DS . 'views' . DS . 'home' . DS . 'signup.php');
			$page->setLayout(APP . DS . 'templates' . DS . 'base.php');
			$page->addVar('request', 'GET');
			$page->addVar('layout_login', 'caca');
			$page->addVar('action', '');
			$page->addVar('layout_jquery', '');
			$page->addVar('layout_bootstrap_js', '');
			$page->addVar('layout_style', '');
			$page->addVar('layout_bootstrap_css', '');
			$page->addVar('layout_home', '');
			$page->addVar('layout_login', '');
			$page->addVar('layout_logout', '');
			$page->addVar('layout_cr_article', '');
			$page->addVar('layout_settings', '');
			$page->addVar('layout_m_users', '');
			$this->response->setPage($page);
			print($this->response->send());*/
			echo "Command not found\n";
		}
	}

	public function		__construct(Kernel $kernel, $argc = NULL, $argv = NULL)
	{
		parent::__construct($kernel);
		if ($argc === NULL)
			$argc = $_SERVER['argc'];
		if ($argv === NULL)
			$argv = $_SERVER['argv'];
		if ($argc > 1)
		{
			$this->setCommands();
			$this->command = $this->loadCommand($argv[1]);
		}
		else
		{
			$this->command = false;
		}
	}
}