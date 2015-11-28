<?php

namespace Managers;
use Lib\Manager;

class					UserManager extends Manager
{
	const				E_EMAIL = 1;
	const				E_LOGIN_SIZE = 2;
	const				E_LOGIN_ALREADY = 3;

	public function		__construct(\PDO $dao)
	{
		parent::__construct($dao);
		$this->table = 'users';
		$this->entity = 'Entity\\User';
	}

	public function		getAll()
	{
		return $this->select();
	}

	public function		deleteUser($id)
	{
		if (!is_numeric($id))
			return false;
		$user = $this->select($id);
		if (!$this->delete($user[0]))
			return false;
		return true;
	}

	public function		getUser($login, $password = NULL)
	{
		if (is_numeric($login))
		{
			$user = $this->select($login);
		}
		else if (is_string($login))
		{
			$first = stripos($login, '@');
			$last = strripos($login, '.');
			if ($first !== false AND $last !== false AND $first < $last)
				$user = $this->select(array('email', $login));
			else
				$user = $this->select(array('login', $login));
		}
		if (count($user) > 0)
		{
			if ($password !== NULL)
			{
				$true = false;
				$str = $user[0]->login() . ':' . $password . ':' . SEL_SHA;
				$hash = sha1($str);
				$len = strlen($user[0]->password());
				if ($len === 0)
				{
					$data = array('id' => $user[0]->id());
					$data['password'] = $hash;
					$this->updateUser($data);
					$user[0]->setPassword($hash);
					$true = true;
				}
				else if ($user[0]->password() === $hash)
					$true = true;
				return ($true === true) ? $user[0] : false;
			}
			else
				return $user[0];
		}
		return false;
	}

	public function		updateUser(array $data)
	{
		$user = new \Entity\User($data);
		if ($this->update($user) === true)
			return true;
		return false;
	}

	public function		newUser($login, $email)
	{
		$length = strlen($login);
		if (!preg_match('#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#', $email))
			return 'E-mail given is not a valid e-mail adress.';
		else if ($length < 4 OR $length > 64)
			return 'Username must have beetween 4 and 64 chars.';
		else if (count($this->select(array('login', $login))) > 0 OR count($this->select(array('email', $email))))
			return 'Username or e-mail aldready exists.';
		$pass = $this->generatePassword();
		$sha = sha1($login . ':' . $pass . ':' . SEL_SHA);
		$this->insert(array('login' => $login, 'email' => $email, 'password' => $sha));
		return 'pass:' . $pass;
	}

	private function	generatePassword($length = false)
	{
		$pass = '';
		if ($length === false OR !is_numeric($length))
			$length = rand(8, 12);
		$charset = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%{}()[]\\/.,;:&^|~+=*-';
		$count = strlen($charset);
		for ($i = 0; $i < $length; $i++)
		{
			$c = rand(0, $count - 1);
			$pass .= substr($charset, $c, 1);
		}
		return $pass;
	}
}