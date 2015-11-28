<?php

namespace Lib;

abstract class			Component
{
	protected			$app;

	public function		__construct(App $app)
	{
		$this->app = $app;
	}
}