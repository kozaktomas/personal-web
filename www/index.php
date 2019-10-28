<?php declare(strict_types=1);

use Nette\DI\Container;

/** @var Container $container */
$container = require __DIR__ . '/../app/bootstrap.php';

$container->getService('application')->run();
