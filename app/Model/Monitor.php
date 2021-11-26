<?php declare(strict_types=1);

namespace Kozak\Tomas\App\Model;

use Nette\Application\Request;
use Nette\Http\Response;
use Prometheus\CollectorRegistry;
use Prometheus\RenderTextFormat;
use Prometheus\Storage\Redis;
use function is_string;
use function round;

class Monitor
{

    private CollectorRegistry $registry;

    public function __construct(string $redisHost, int $redisPort, int $redisDatabase)
    {
        $redis = new Redis(
            [
                'host' => $redisHost,
                'port' => $redisPort,
                'database' => $redisDatabase,
                'password' => null,
                'timeout' => 0.1, // in seconds
                'read_timeout' => '10', // in seconds
                'persistent_connections' => false,
            ]
        );

        $this->registry = new CollectorRegistry($redis, true);
    }

    public function logRequest(Request $request, Response $response, float $durationNs): void
    {
        $labels = [
            'method', // POST, GET, ...
            'status_code', // 200, 301, ...
            'route', // Homepage:default, Homepage:contact
        ];
        $buckets = [
            0.010,
            0.025,
            0.050,
            0.100,
            0.250,
            0.500,
            1.000,
            2.500,
            5.000,
            10.000
        ];
        $histogram = $this->registry->getOrRegisterHistogram('', 'http_request_duration_seconds', 'HTTP request info histogram', $labels, $buckets);

        $duration = round($durationNs * 10e-9, 3);
        $histogram->observe($duration, [
            'method' => (string)$request->getMethod(),
            'status_code' => (string)$response->getCode(),
            'route' => $this->buildRoute($request),
        ]);
    }

    public function generateTextOutput(): string
    {
        $renderer = new RenderTextFormat();
        return $renderer->render($this->registry->getMetricFamilySamples());
    }

    private function buildRoute(Request $request): string
    {
        $action = $request->getParameter('action');
        if (!is_string($action)) {
            $action = '';
        }
        return $request->getPresenterName() . ':' . $action;
    }
}