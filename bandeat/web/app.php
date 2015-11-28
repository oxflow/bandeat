<?php

require_once __DIR__ . '/../lib/Kernel.php';

use Lib\Kernel as K;

$kernel = K::getInstance();
$app = $kernel->getApp();
$app->run();