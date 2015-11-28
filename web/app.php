<?php

if (!isset($_GET['app']))
	exit(0);

define('DIR', dirname(dirname(__FILE__)));

require_once('config.php');
require_once(LIB . DS . 'Autoloader.php');

$load = new Autoloader('Entity', ENTITY);
$load->register();

$load = new Autoloader('Managers', MANAGER);
$load->register();

$load = new Autoloader('Lib', LIB);
$load->register();

// LOAD APPLICATIONS

$load = new Autoloader('Frontend', FRONTEND);
$load->register();

$load = new Autoloader('Backend', BACKEND);
$load->register();

$className = $_GET['app'] . '\\' . $_GET['app'] . 'Application';
$app = new $className();
$app->run();