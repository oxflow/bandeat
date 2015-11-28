<?php

namespace Console;

use Console\Console;

abstract class			Command
{
	protected			$action;
	protected			$name;
	protected			$console;
	protected			$stdin;

	public function		__construct(Console $console, $name)
	{
		$this->action = NULL;
		$this->name = $name;
		$this->console = $console;
		$this->stdin = fopen('php://stdin', 'r');
	}

	public function		execute()
	{
		if ($this->action === NULL)
			throw new \LogicException('Action not found');
		$action = $this->action;
		$this->$action();
		fclose($this->stdin);
	}
}