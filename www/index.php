<?php declare(strict_types=1);

use Kozak\Tomas\App\Bootstrap;
use Nette\Application\Application;

require __DIR__ . '/../vendor/autoload.php';

$container = Bootstrap::boot();
$application = $container->getByType(Application::class);
$application->run();
