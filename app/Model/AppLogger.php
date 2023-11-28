<?php declare(strict_types=1);

namespace Kozak\Tomas\App\Model;

use Nette\Utils\Json;
use Tracy\ILogger;
use Tracy\Logger;

class AppLogger implements ILogger
{
    private const string LOG_APP = 'kozak_in';

    private static string $hash = '';

    public function log(mixed $value, string $level = self::INFO): void
    {
        try {
            $encoded = Json::encode($this->prepareLog($value, $level));
        } catch (\Throwable) {
            // this should never happen
            @file_put_contents(
                'php://stdout',
                "Application logger failed\n",
                FILE_APPEND | LOCK_EX
            );
            return;
        }

        @file_put_contents(
            'php://stdout',
            $encoded . "\n",
            FILE_APPEND | LOCK_EX
        );
    }

    /**
     * @return array<string,string>
     */
    private function prepareLog(mixed $value, string $level): array
    {
        return [
            'log_timestamp' => (new \DateTime())->format('Y-m-d\TH:i:s.u\Z'),
            'log_level' => $level,
            'log_app' => self::LOG_APP,
            'log_request' => $this->getHash(),
            'message' => Logger::formatMessage($value),
        ];
    }

    /**
     * Hash is unique for whole request
     * It's much easier to identify logs from the same request
     */
    private function getHash(): string
    {
        if (self::$hash === '') {
            self::$hash = \uniqid('', true);
        }

        return self::$hash;
    }
}