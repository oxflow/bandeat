<?php

namespace Console\Commands;

use Console\Command,
	Console\Console;

class					Manager extends Command
{
	public function		__construct(Console $console, $name)
	{
		parent::__construct($console, $name);
		if ($name === 'generate:manager')
			$this->action = 'manager';
	}

	public function		manager($namespace = false, $entity = false)
	{
		if (!$namespace)
		{
			while (!$namespace)
			{
				echo "Namespace : ";
				fscanf($this->stdin, "%s\n", $name);
				if (preg_match("#^Manager\\\\([A-Z][A-Za-z]+)$#", $name, $matches))
				{
					echo "\n";
					$path = MANAGER;
					$manager = $matches[1];
					$namespace = $name;
				}
				else if (preg_match("#^([A-Z][A-Za-z]+)\\\\Manager\\\\([A-Z][A-Za-z]+)$#", $name, $matches))
				{
					echo "\n";
					$app = $matches[1];
					$path = $this->console->getBundlepath($app);
					if ($path !== false)
					{
						$manager = $matches[2];
						$path .= DS . 'models' . DS . 'managers';
						$namespace = $name;
						if (!file_exists($path))
							mkdir($path, 0755, true);
					}
					else
					{
						echo sprintf("L'application '%s' n'existe pas.\n\n", $app);
					}
				}
				else
				{
					echo "Le nom du manager doit commencer par une majuscule et doit contenir au moins 2 caracteres.\n";
					echo "Le namespace doit se presenter sous cette forme : Application\\Manager\\Namespace\n\n";
				}
			}
		}
		else if (preg_match("#^(([A-Z][A-Za-z]+)\\\\)?Manager\\\\([A-Z][A-Za-z]+)$#", $namespace, $matches))
		{
			$app = $matches[2];
			$manager = $matches[3];
			$path = $this->console->getBundlepath($app);
			if ($path !== false)
			{
				$path .= DS . 'models' . DS . 'managers';
				if (!file_exists($path))
					mkdir($path, 0755, true);
			}
			else
				$path = MANAGER;
		}
		else
			throw new \LogicException(sprintf("Namespace '%s' isn't correct", $namespace));
		$path .= DS . $manager . 'Manager.php';
		if (!$entity)
		{
			while (!$entity)
			{
				echo "Entite : ";
				fscanf($this->stdin, "%s\n", $name);
				if (preg_match("#^Entity\\\\([A-Z][A-Za-z]+)$#", $name, $matches))
				{
					echo "\n";
					$entity = $name;
				}
				else if (preg_match("#^([A-Z][A-Za-z]+)\\\\Entity\\\\([A-Z][A-Za-z]+)$#", $name, $matches))
				{
					echo "\n";
					$entity = $name;
				}
				else
				{
					echo "Le nom de l'entite doit commencer par une majuscule et doit contenir au moins 2 caracteres.\n";
					echo "Le namespace doit se presenter sous cette forme : Application\\Entity\\Namespace\n\n";
				}
			}
		}
		else if (!preg_match("#^(([A-Z][A-Za-z]+)\\\\)?Entity\\\\([A-Z][A-Za-z]+)$#", $entity))
			throw new \LogicException(sprintf("Entity '%s' isn't correct", $entity));
		$table = false;
		while (!$table)
		{
			echo "Table SQL : ";
			fscanf($this->stdin, "%s\n", $name);
			if (preg_match("#^([A-Za-z0-9_-]+)$#", $name, $matches))
			{
				echo "\n";
				$table = $name;
			}
			else
				echo "Le nom de la table ne peut contenir que des lettres, des chiffres ou des tirets (- et _).\n\n";
		}
		$this->generateManager($manager, $namespace, $entity, $table, $path);
		echo "Le manager a ete cree dans le fichier $path\n\n";
	}

	private function	generateManager($manager, $ns, $entity, $table, $path)
	{
		$class = $manager . 'Manager';
		$variable = strtolower($manager);
		$entity = str_replace("\\", "\\\\", $entity);
		$tab = explode('\\', $ns);
		array_splice($tab, -1);
		$ns = implode('\\', $tab);
		$str = "<?php

namespace $ns;

use Lib\Manager;

class					$class extends Manager
{
	public function		__construct(" . '$dao' . ")
	{
		parent::__construct(" . '$dao' . ");
		" . '$this->table' . " = '$table';
		" . '$this->entity' . " = '$entity';
	}

	public function		getList()
	{
		return " . '$this->select()' . ";
	}

	public function		create$manager(array " . '$data' . ", " . '$timestamp' . " = true)
	{
		if (" . '$timestamp' . " === true)
			$" . 'data[\'timestamp\'] = date("Y-m-d H:i:s", time());' . "
		else if (preg_match('#^[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}$#', " . '$timestamp' . "))
			$" . 'date[\'timestamp\'] = $timestamp' . ";
		if (" . '$this->insert($data)' . ")
			return true;
		return false;
	}

	public function		get$manager(" . '$id' . ")
	{
		$" . $variable . " = " . '$this->select($id)' . ";
		if (count($" . $variable . ") > 0)
			return $" . $variable . "[0];
		return false;
	}

	public function		update$manager(array " . '$data' . ")
	{
		if (!isset(" . '$data[\'id\']' . "))
			return false;
		$" . $variable . ' = $this->get' . $manager . '($data[\'id\'])' . ";
		$" . $variable . '->hydrate($data);' . "
		if ($" . 'this->update($' . $variable . "))
			return true;
		return false;
	}

	public function		delete$manager(" . '$id' . ")
	{
		$" . $variable . " = " . '$this->get' . $manager . '($id)' . ";
		if (" . '$this->delete($' . $variable . "))
			return true;
		return false;
	}
";
		$str .= "}\n";
		file_put_contents($path, $str);
	}
}