<?php

require __DIR__ . '/../../vendor/autoload.php';

use Kozak\Tomas\App\Model\AgeCalculator;
use Tester\Assert;

$ac = new AgeCalculator();
$tz = new \DateTimeZone(AgeCalculator::BORN_TIMEZONE);

Assert::equal(10, $ac->getAge(new DateTimeImmutable('2001-05-01', $tz)));
Assert::equal(20, $ac->getAge(new DateTimeImmutable('2011-05-01', $tz)));
Assert::equal(29, $ac->getAge(new DateTimeImmutable('2021-01-01', $tz)));