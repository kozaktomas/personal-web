<?php declare(strict_types=1);

namespace Kozak\Tomas\App;

use Kozak\Tomas\App\Model\AppLogger;
use Nette\Bootstrap\Configurator;
use Nette\DI\Container;
use Tracy\Debugger;
use function getenv;

final class Bootstrap
{
    public static function boot(): Container
    {
        Debugger::setLogger(new AppLogger());
        $configurator = new Configurator;
        $configurator->setDebugMode(self::onDev());
        $configurator->enableTracy(__DIR__ . '/../log');
        $configurator->setTempDirectory(__DIR__ . '/../temp');
        $configurator->addConfig(__DIR__ . '/config/config.neon');
        $configurator->addStaticParameters([
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


