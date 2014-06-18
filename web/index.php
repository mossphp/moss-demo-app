<?php
require __DIR__ . '/../vendor/autoload.php';

$moss = new \Moss\Kernel\App(require __DIR__ . '/../src/Moss/Sample/bootstrap.php');
$moss->run()->send();