<?php

namespace Lib;

class					Response extends Component
{
	private				$page;

	public function		addHeader($header)
	{
		if (!is_string($header))
			throw new \LogicException("Header must be a string");
		header($header);
	}

	public function		redirect404()
	{
		$this->page = new Page();
		$this->page->setLayout(APP . DS . 'templates' . DS . 'base.php');
		$this->page->setView(APP . DS . 'templates' . DS . 'error.php');
		$link = $this->app->link();

		// STYLES
		$this->page->addVar('layout_jquery', $link->getTemplate('js/jquery.min.js'));
		$this->page->addVar('layout_bootstrap_js', $link->getTemplate('js/bootstrap.min.js'));
		$this->page->addVar('layout_style', $link->getTemplate('css/style.css'));
		$this->page->addVar('layout_bootstrap_css', $link->getTemplate('css/bootstrap.min.css'));

		// LINKS
		$this->page->addVar('layout_home', $link->getUrl('Home', 'index'));
		$this->page->addVar('layout_login', $link->getUrl('Home', 'login'));
		$this->page->addVar('layout_logout', $link->getUrl('Home', 'logout'));
		$this->page->addVar('layout_cr_article', $link->getUrl('Blog', 'create'));
		$this->page->addVar('layout_settings', $link->getUrl('Home', 'settings'));
		$this->page->addVar('layout_m_users', $link->getUrl('Admin', 'users'));
		$this->send($this->page);
	}

	public function		redirect($location)
	{
		if (!is_string($location))
			throw new \LogicException("Redirection must be a string");
		header('Location: ' . $location);
		exit ;
	}

	public function		setPage(Page $page)
	{
		$this->page = $page;
		return $this;
	}

	public function		send()
	{
		exit($this->page->render());
	}

	public function		setCookie($name, $value, $expire = 0, $path = '', $domain = '', $httpOnly = true)
	{
		if ($expire === 0 OR !is_int($expire))
			$expire = time() + 60 * 60 * 24 * 30;
		setcookie($name, $value, $expire, $path, $domain, false, $httpOnly);
	}

	public function		unsetCookie($name)
	{
		setcookie($name, NULL, -1);
		unset($_COOKIE[$name]);
	}
}