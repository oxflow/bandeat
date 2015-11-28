<?php

if (!defined('DIR'))
	exit(0);

define('DS', DIRECTORY_SEPARATOR);
define('WEB', DIR . DS . 'web');
define('APP', DIR . DS . 'app');
define('SRC', DIR . DS . 'src');
define('LIB', DIR . DS . 'motor');
define('MANAGER', DIR . DS . 'models' . DS . 'managers');
define('ENTITY', DIR . DS . 'models' . DS . 'entities');

// APPLICATIONS

define('FRONTEND', DIR . DS . 'src' . DS . 'frontend');
define('BACKEND', DIR . DS . 'src' . DS . 'backend');

// OTHERS

define('SEL_SHA', 'frame3214work@tw0rk');