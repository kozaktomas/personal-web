<?php declare(strict_types=1);

namespace Kozak\Tomas\App;

use Nette\Configurator;
use Nette\DI\Container;
use function getenv;

final class Bootstrap
{
    public static function boot(): Container
    {
        $configurator = new Configurator;
        $configurator->setDebugMode(self::onDev());
        $configurator->enableDebugger(__DIR__ . '/../log');
        $configurator->setTempDirectory(__DIR__ . '/../temp');
        $configurator->addConfig(__DIR__ . '/config/config.neon');
        $configurator->addParameters([
            'discord_webhook_url' => getenv('DISCORD_WEBHOOK_URL'),
            'monitor_redis_host' => getenv('MONITOR_REDIS_HOST'),
            'monitor_redis_port' => (int)getenv('MONITOR_REDIS_PORT'),
            'monitor_redis_database' => (int)getenv('MONITOR_REDIS_DATABASE')
        ]);

        return $configurator->createContainer();
    }

    private static function onDev(): bool
    {
        return (bool)getenv('DEV');
    }
}


