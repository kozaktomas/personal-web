<?php declare(strict_types=1);

use Kozak\Tomas\App\Bootstrap;
use Kozak\Tomas\App\Model\Monitor;
use Nette\Application\Application;
use Nette\Http\Response;

$start = hrtime(true);

require __DIR__ . '/../vendor/autoload.php';

$container = Bootstrap::boot();
$application = $container->getByType(Application::class);
$application->run();
$duration = hrtime(true) - $start;

// monitoring
$requests = $application->getRequests();
if (count($requests) > 0) {
    $request = array_shift($requests);
    $response = $container->getByType(Response::class);
    $monitor = $container->getByType(Monitor::class);
    $monitor->logRequest($request, $response, $duration);
}



