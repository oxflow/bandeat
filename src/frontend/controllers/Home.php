<?php

namespace Frontend\Controllers;
use Lib\Controller,
	Lib\Link,
	Lib\Route,
	Lib\App,
	Lib\Session;

class					Home extends Controller
{
	public function		__construct(App $app, $controller, $action, $vars)
	{
		parent::__construct($app, $controller, $action, $vars);
		$this->page->addVar('request', $this->request);
	}

	public function		indexAction()
	{
		$this->page->addVar('layout_title', 'Home');
		$manager = $this->managers->getManager('Article');
		$this->page->addVar('articles', $manager->getList());
		$this->page->addVar('userManager', $this->managers->getManager('User'));
		$this->page->addVar('link', $this->link);
	}

	public function		settingsAction()
	{
		$users = $this->managers->getManager('User');
		$user = $users->getUser(Session::get('id'));
			if (!$user)
				$this->app->response()->redirect($this->link->getUrl('Home', 'login'));
		if ($this->request === "POST")
		{
			$data = array();
			$data['login'] = $this->app->request()->postData('login');
			$password = $this->app->request()->postData('password', false);
			$hash = sha1($user->login() . ':' . $password . ':' . SEL_SHA);
			if (empty($password))
				$this->page->addVar('error', 'Password is empty.');
			else if ($hash !== $user->password())
				$this->page->addVar('error', 'Password is not correct.');
			else
			{
				$data['email'] = $this->app->request()->postData('email');
				$pass = $this->app->request()->postData('change_password', false);
				$confirm = $this->app->request()->postData('confirm_password', false);
				if (!empty($pass) AND strlen($pass) > 5 AND $pass === $confirm)
				{
					$str = $data['login'] . ':' . $pass . ':' . SEL_SHA;
					$data['password'] = sha1($str);
				}
				else if (!empty($pass) AND strlen($pass) < 6)
				{
					$this->page->addVar('error', 'Password must have 6 characters or more.');
					return (0);
				}
				else
				{
					$str = $data['login'] . ':' . $password . ':' . SEL_SHA;
					$data['password'] = sha1($str);
				}
				$data['id'] = $user->id();
				if ($users->updateUser($data))
				{
					$data['pass'] = $data['password'];
					unset($data['password']);
					$this->page->addVar('success', 'Your settings have been updated.');
					Session::reset($data);
				}
				else
					$this->page->addVar('error', 'Please contact an administrator.');
			}
		}
		else
		{
			$this->page->addVar('login', $user->login());
			$this->page->addVar('email', $user->email());
			$this->page->addVar('layout_title', 'Settings');
			$this->page->addVar('action', $this->link->getUrl('Home', 'settings'));
		}
	}

	public function		logoutAction()
	{
		$this->page->addVar('layout_title', 'Logout');
		$this->app->response()->unsetCookie('remember');
		Session::destroy();
	}

	public function		loginAction()
	{
		if ($this->request === "POST")
		{			$login = $this->app->request()->postData('login');
			$password = $this->app->request()->postData('password', false);
			$users = $this->managers->getManager('User');
			$req = $users->getUser($login, $password);
			if ($req === false)
				$this->page->addVar('error', 'Wrong username or password.');
			else
			{
				$admin = $req->admin();
				$login = $req->login();
				$password = sha1($login . ':' . $password . ':' . SEL_SHA);
				if ($this->app->request()->postExist('remember'))
					$this->app->response()->setCookie('remember', $login);
				$vars = array('auth' => true, 'login' => $login, 'pass' => $password, 'login_time' => time(), 'admin' => $admin, 'id' => $req->id());
				Session::set($vars);
			}
		}
		else
			$this->page->addVar('action', $this->link->getUrl('Home', 'login'));
		$this->page->addVar('u_signup', $this->link->getUrl('Home', 'signup'));
		$this->page->addVar('layout_title', 'Login');
	}

	public function		signupAction()
	{
		if ($this->request === "POST")
		{
			$captcha = $this->app->request()->postData('captcha');
			if ($captcha !== "42" AND strtolower($captcha) !== "quarante-deux")
			{
				$this->page->addVar('error', 'Erreur de captcha.');
				return ;
			}
			$users = $this->managers->getManager('User');
			$req = $users->newUser($this->app->request()->postData('login'), $this->app->request()->postData('email'));
			if (substr($req, 0, 5) !== "pass:")
				$this->page->addVar('error', $req);
			else
			{
				$this->page->addVar('success', 'An e-mail with your password has been sent to your adress.');
				$this->page->addVar('pass', substr($req, 5));
			}
		}
		$this->page->addVar('layout_title', 'Sign up');
		$this->page->addVar('action', $this->link->getUrl('Home', 'signup'));
	}
}