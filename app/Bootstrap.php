<?php declare(strict_types=1);

namespace Kozak\Tomas\App;

use Nette\Configurator;
use Nette\DI\Container;

final class Bootstrap
{
    public static function boot(): Container
    {
        $configurator = new Configurator;
        $configurator->setDebugMode(self::onDev());
        $configurator->enableDebugger(__DIR__ . '/../log');
        $configurator->setTempDirectory(__DIR__ . '/../temp');
        $configurator->addConfig(__DIR__ . '/config/config.neon');

        return $configurator->createContainer();
    }

    private static function onDev(): bool
    {
        return (bool)\getenv('DEV');
    }
}


