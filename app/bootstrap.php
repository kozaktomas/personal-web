<?php declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

$configurator = new Nette\Configurator;

$dev = (bool)\getenv('DEV');

if ($dev) {
	$configurator->setDebugMode(true);
}
$configurator->enableDebugger(__DIR__ . '/../log');

$configurator->setTempDirectory(__DIR__ . '/../temp');

$configurator->addConfig(__DIR__ . '/config/config.neon');


$container = $configurator->createContainer();


return $container;
