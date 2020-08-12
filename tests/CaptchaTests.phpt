<?php

require __DIR__ . '/../vendor/autoload.php';

use Kozak\Tomas\App\Model\CaptchaDto;
use Kozak\Tomas\App\Model\CaptchaService;
use Tester\Assert;

// Serializer tests
$dto = new CaptchaDto(3, 31, 2, 4); // 31*x^3 + 3 [2, 4]
$serialized = $dto->serialize();
Assert::equal('31|3|2|4', $serialized);
$dto = CaptchaDto::deserialize($serialized);
Assert::equal(3, $dto->d0);
Assert::equal(31, $dto->d3);
Assert::equal(2, $dto->lowerLimit);
Assert::equal(4, $dto->upperLimit);


// Service tests
$service = new CaptchaService();

Assert::true($service->isCorrect(CaptchaDto::deserialize('31|3|2|4'), 1866));
Assert::true($service->isCorrect(CaptchaDto::deserialize('60|2|2|4'), 3604));
Assert::true($service->isCorrect(CaptchaDto::deserialize('32|3|2|4'), 1926));

Assert::false($service->isCorrect(CaptchaDto::deserialize('32|3|2|4'), 0));
Assert::false($service->isCorrect(CaptchaDto::deserialize('32|3|2|4'), -1926));
Assert::false($service->isCorrect(CaptchaDto::deserialize('32|3|2|4'), PHP_INT_MAX));
Assert::false($service->isCorrect(CaptchaDto::deserialize('32|3|2|4'), PHP_INT_MIN));