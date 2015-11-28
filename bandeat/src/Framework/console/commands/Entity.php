<?php

namespace Console\Commands;

use Console\Command,
	Console\Console;

class					Entity extends Command
{
	public function		__construct(Console $console, $name)
	{
		parent::__construct($console, $name);
		if ($name === 'generate:entity')
			$this->action = 'entity';
	}

	public function		entity()
	{
		$entity = false;
		while (!$entity)
		{
			echo "Namespace : ";
			fscanf($this->stdin, "%s\n", $name);
			if (preg_match("#^Entity\\\\([A-Z][A-Za-z]+)$#", $name, $matches))
			{
				echo "\n";
				$path = ENTITY;
				$entity = $matches[1];
				$namespace = $name;
			}
			else if (preg_match("#^([A-Z][A-Za-z]+)\\\\Entity\\\\([A-Z][A-Za-z]+)$#", $name, $matches))
			{
				echo "\n";
				$app = $matches[1];
				$path = $this->console->getBundlepath($app);
				if ($path !== false)
				{
					$entity = $matches[2];
					$path .= DS . 'models' . DS . 'entities';
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
				echo "Le nom de l'entite doit commencer par une majuscule et doit contenir au moins 2 caracteres.\n";
				echo "Le namespace doit se presenter sous cette forme : Application\\Entity\\Namespace\n\n";
			}
		}
		$path .= DS . $entity . '.php';

		$vars = array();
		$stop = false;
		while (!$stop)
		{
			$name = false;
			while (!$name)
			{
				echo "Nom de la variable : ";
				fscanf($this->stdin, "%s\n", $varname);
				if (!strlen($varname))
				{
					if (count($vars) === 0)
						echo "Vous devez ajouter au moins une variable a l'entite.\n\n";
					else
					{
						$stop = true;
						echo "\n";
						break;
					}
				}
				else if (preg_match("#^[A-Za-z][A-Za-z0-9]*$#", $varname))
				{
					$name = $varname;
					echo "\n";
				}
				else
					echo "Le nom doit commencer par une lettre et ne pas contenir de caracteres speciaux.\n\n";
				unset($varname);
			}
			if ($stop)
				break;
			$type = false;
			while (!$type)
			{
				echo "Type (int|number|string|timestamp|bool|null) [null] : ";
				fscanf($this->stdin, "%s\n", $str);
				if (!strlen($str))
					$type = 'null';
				else if ($str === 'int' OR $str === 'string' OR $str === 'number' OR $str === 'timestamp' OR $str === 'bool' OR $str === 'null')
					$type = $str;
				unset($str);
				if (!$type)
					echo "Le type doit etre l'une de ces valeurs : int,number,string,timestamp,bool ou null.\n\n";
				else
				{
					$vars[] = array('name' => $name, 'type' => $type);
					echo "\n";
				}
			}
		}
		$this->generateEntity($entity, $vars, $path, $namespace);
		echo "L'entite a ete creee dans le fichier $path\n\n";
		$ans = false;
		while (!$ans)
		{
			echo "Voulez-vous generer le manager automatiquement ? [O/n] ";
			fscanf($this->stdin, "%s\n", $ans);
			if (preg_match("#^[OoYy]$#", $ans) OR strtolower($ans) === "yes" OR strtolower($ans) === "oui")
				$val = true;
			else if (preg_match("#^[nN]$#", $ans) OR strtolower($ans) === "no" OR strtolower($ans) === "non")
				$val = false;
			else if ($ans === false)
			{
				$val = true;
				$ans = true;
			}
			else
			{
				$ans = false;
				echo "Vous devez repondre oui ou non.\n\n";
			}
		}
		if ($val)
		{
			echo "\n";
			$manager = $this->console->loadCommand('generate:manager');
			$entity = $namespace;
			$namespace = str_replace("Entity", "Manager", $namespace);
			$manager->manager($namespace, $entity);
		}
	}

	private function	generateEntity($entity, $data, $path, $namespace)
	{
		$tab = explode('\\', $namespace);
		array_splice($tab, -1);
		$ns = implode('\\', $tab);
		$str = "<?php

namespace $ns;

use Lib\Entity;

class					$entity extends Entity
{
";
		foreach ($data as $var)
		{
			$str .= "\tprivate\t\t\t\t$" . $var['name'] . ";\n";
		}
		foreach ($data as $var)
		{
			$str .= "\n";
			$str .= "\tpublic function\t\t" . $var['name'] . "()\n";
			$str .= "\t{\n";
			$str .= "\t\treturn " . '$this->' . $var['name'] . ";\n";
			$str .= "\t}\n";
		}
		foreach ($data as $var)
		{
			$str .= "\n";
			$str .= "\tpublic function\t\tset" . ucfirst($var['name']) . '($' . $var['name'] . ')' . "\n";
			$str .= "\t{\n";
			if ($var['type'] === "int")
			{
				$str .= "\t\t" . '$this->' . $var['name'] . ' = intval($' . $var['name'] . ");\n";
			}
			else if ($var['type'] === "number")
			{
				$str .= "\t\tif (is_numeric($" . $var['name'] . "))\n";
				$str .= "\t\t\t" . '$this->' . $var['name'] . ' = $' . $var['name'] . ";\n";
			}
			else if ($var['type'] === "string")
			{
				$str .= "\t\tif (is_string($" . $var['name'] . "))\n";
				$str .= "\t\t\t" . '$this->' . $var['name'] . ' = $' . $var['name'] . ";\n";
			}
			else if ($var['type'] === "bool")
			{
				$str .= "\t\tif (is_bool($" . $var['name'] . "))\n";
				$str .= "\t\t\t" . '$this->' . $var['name'] . ' = $' . $var['name'] . ";\n";
			}
			else if ($var['type'] === "timestamp")
			{
				$str .= "\t\tif (is_numeric($" . $var['name'] . "))\n";
				$str .= "\t\t\t" . '$this->' . $var['name'] . ' = date("Y-m-d H:i:s", $' . $var['name'] . ");\n";
				$str .= "\t\telse if (preg_match('#^[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}$#', $" . $var['name'] . "))\n";
				$str .= "\t\t\t" . '$this->' . $var['name'] . ' = $' . $var['name'] . ";\n";
			}
			else
				$str .= "\t\t" . '$this->' . $var['name'] . ' = $' . $var['name'] . ";\n";
			$str .= "\t\treturn " . '$this;' . "\n";
			$str .= "\t}\n";
		}
		$str .= "}\n";
		file_put_contents($path, $str);
	}
}