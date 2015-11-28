<?php

namespace Entity;
use Lib\Entity;

class					User extends Entity
{
	private				$login;
	private				$email;
	private				$password;
	private				$admin;
	private				$timestamp;

	public function		login()
	{
		return $this->login;
	}

	public function		email()
	{
		return $this->email;
	}

	public function		password()
	{
		return $this->password;
	}

	public function		admin()
	{
		return $this->admin;
	}

	public function		timestamp()
	{
		return $this->timestamp;
	}

	public function		setLogin($login)
	{
		if (is_string($login))
			$this->login = $login;
		return $this;
	}

	public function		setEmail($email)
	{
		if (is_string($email))
			$this->email = $email;
		return $this;
	}

	public function		setPassword($password)
	{
		if (is_string($password))
			$this->password = $password;
		return $this;
	}

	public function		setAdmin($admin)
	{
		if (is_bool($admin))
			$this->admin = $admin;
		return $this;
	}

	public function		setTimestamp($timestamp)
	{
		$this->timestamp = $timestamp;
		return $this;
	}
}