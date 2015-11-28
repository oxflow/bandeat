<?php

namespace Lib;

class					Page
{
	private				$layout;
	private				$view;
	private				$vars = array();

	public function		render()
	{
		if (!file_exists($this->view))
			throw new \RuntimeException(sprintf('Content file "%s" does not exists', $this->view));
		extract($this->vars);
		ob_start();
		require_once $this->view;
		$content_layout = ob_get_clean();
		if (file_exists($this->layout))
		{
			ob_start();
			require_once $this->layout;
			return ob_get_clean();
		}
		return $content_layout;
	}

	public function		addVar($key, $value)
	{
		if (!is_string($key))
			throw new \LogicException(sprintf('Key "%s" must be a string', $key));
		$this->vars[$key] = $value;
		return $this;
	}

	public function		setLayout($layout)
	{
		if (!is_string($layout))
			throw new \LogicExcepion('Layout must be a string');
		$this->layout = $layout;
	}

	public function		setView($view)
	{
		if (!is_string($view))
			throw new \LogicExcepion('View must be a string');
		$this->view = strtolower($view);
	}
}