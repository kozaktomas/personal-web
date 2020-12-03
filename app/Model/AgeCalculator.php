<?php declare(strict_types=1);

namespace Kozak\Tomas\App\Model;

use DateTime;
use DateTimeImmutable;

final class AgeCalculator
{
    public const BORN_TIMEZONE = 'Europe/Prague';

    private const BORN_DATE = '21/04/1991';

    public function getAge(?DateTimeImmutable $point = null): int
    {
        $tz = new \DateTimeZone(self::BORN_TIMEZONE);
        if ($point === null) {
            $point = new DateTimeImmutable('now', $tz);
        }
        
        $born = DateTime::createFromFormat('d/m/Y', self::BORN_DATE, $tz);
        if (!$born instanceof DateTime) {
            throw new AgeCalculationException('Could not parse date of birth.');
        }

        $age = $born->diff($point);

        if ($age === false) {
            throw new AgeCalculationException('Could not calculate age');
        }

        return $age->y;
    }

}