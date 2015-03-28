<?php
require __DIR__ . '/../vendor/autoload.php';

$config = new \Moss\Config\Config(require __DIR__ . '/../src/Moss/Sample/bootstrap.php');

$moss = new \Moss\Kernel\App($config);
$moss->run()->send();
