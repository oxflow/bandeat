<?php

namespace Backend\Controllers;
use Lib\Controller,
	Lib\Link,
	Lib\Route,
	Lib\App,
	Lib\Session;

class					Admin extends Controller
{
	public function		__construct(App $app, $controller, $action, $vars)
	{
		parent::__construct($app, $controller, $action, $vars);
		$this->page->addVar('request', $this->request);
		$this->page->addVar('layout_title', 'Admin panel');
		if (Session::get('admin') < 3)
			$this->app->response()->redirect404();
	}

	public function		deleteUserAction($id = NULL)
	{
		$users = $this->managers->getManager('User');
		if ($id !== NULL AND is_numeric($id))
		{
			$user = $users->getUser($id);
			if ($user === false)
				$this->page->addVar('error', 'Unknown user');
			else
			{
				$this->page->addVar('action', $this->link->getUrl('Admin', 'deleteUser'));
				$this->page->addVar('id', $id);
			}
		}
		else
		{
			$smode = Session::get('admin');
			$user = $users->getUser($this->app->request()->postData('id'));
			if ($user !== false AND ($smode > $user->admin() OR $smode == 4) AND $users->deleteUser($user->id()))
				$this->page->addVar('success', 'User have been deleted');
			else
				$this->page->addVar('error', 'User have not been deleted');
		}
	}

	public function		usersAction($id = NULL)
	{
		$smode = Session::get('admin');
		$request = $this->app->request();
		$users = $this->managers->getManager('User');
		if ($this->request === "POST")
		{
			$adminlvl = $request->postData('admin');
			$admin = ($adminlvl > 0 AND $adminlvl < 5) ? $adminlvl : 0;
			$data = array(
				'id' => $request->postData('id'),
				'login' => $request->postData('login'),
				'email' => $request->postData('email'),
				'admin' => $admin
			);
			if (strlen($request->postData('password')) > 0)
				$data['password'] = sha1($data['login'] . ':' . $request->postData('password', false) . ':' . SEL_SHA);
			else
				$data['password'] = '';
			if (($smode == 4 OR $admin <= $smode) AND $users->updateUser($data))
				$this->page->addVar('success', 'User have been updated !');
			else
				$this->page->addVar('error', 'User have not been updated');
		}
		$this->page->addVar('users', $users->getAll());
		$this->page->addVar('link', $this->link);
		$this->page->addVar('user_admin', $smode);
		if ($this->request === "GET" AND $id !== NULL AND is_numeric($id))
		{
			$user = $users->getUser($id);
			if ($user !== false AND $smode < 4 AND $user->admin() >= $smode)
				$this->page->addVar('error', 'You can\'t edit this user');
			else if (!empty($user))
			{
				$admin = array(
					($user->admin() == 0) ? " selected" : "",
					($user->admin() == 1) ? " selected" : "",
					($user->admin() == 2) ? " selected" : "",
					($user->admin() == 3) ? " selected" : "",
					($user->admin() == 4) ? " selected" : ""
				);
				$this->page->addVar('action', $this->link->getUrl('Admin', 'users'));
				$this->page->addVar('edit', $user);
				$this->page->addVar('id', $id);
				$this->page->addVar('admin', $admin);
			}
			else
				unset($id);
		}
	}
}