<?php
const WEB_DIR = __DIR__;

require WEB_DIR . '/../vendor/autoload.php';

$config = new \Moss\Config\Config(require WEB_DIR . '/../src/Moss/Sample/bootstrap.php');

$moss = new \Moss\Kernel\App($config);
$moss->run()->send();
