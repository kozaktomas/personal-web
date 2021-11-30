<?php

declare(strict_types=1);

namespace Kozak\Tomas\App\Model;

final class CaptchaService
{
    /**
     * @var array<array<int>>
     * polynoms with int result
     * formula - ax^3 + b
     * [0] => a
     * [1] => b
     * [2] => upper limit
     * [3] => lower limit
     */
    private static array $precoputed = [
        [2, 2, 4, 2],
        [2, 3, 4, 2],
        [2, 4, 4, 2],
        [2, 5, 4, 2],
        [2, 6, 4, 2],
        [2, 7, 4, 2],
        [2, 8, 4, 2],
        [2, 9, 4, 2],
        [2, 10, 4, 2],
        [2, 11, 4, 2],
        [2, 12, 4, 2],
        [2, 13, 4, 2],
        [2, 14, 4, 2],
        [2, 15, 4, 2],
        [2, 16, 4, 2],
        [2, 17, 4, 2],
        [2, 18, 4, 2],
        [2, 19, 4, 2],
        [2, 20, 4, 2],
        [2, 21, 4, 2],
        [2, 22, 4, 2],
        [2, 23, 4, 2],
        [2, 24, 4, 2],
        [2, 25, 4, 2],
        [2, 26, 4, 2],
        [2, 27, 4, 2],
        [2, 28, 4, 2],
        [2, 29, 4, 2],
        [2, 30, 4, 2],
        [2, 31, 4, 2],
        [2, 32, 4, 2],
        [2, 33, 4, 2],
        [2, 34, 4, 2],
        [2, 35, 4, 2],
        [2, 36, 4, 2],
        [2, 37, 4, 2],
        [2, 38, 4, 2],
        [2, 39, 4, 2],
        [2, 40, 4, 2],
        [2, 41, 4, 2],
        [2, 42, 4, 2],
        [2, 43, 4, 2],
        [2, 44, 4, 2],
        [2, 45, 4, 2],
        [2, 46, 4, 2],
        [2, 47, 4, 2],
        [2, 48, 4, 2],
        [2, 49, 4, 2],
        [2, 50, 4, 2],
        [2, 51, 4, 2],
        [2, 52, 4, 2],
        [2, 53, 4, 2],
        [2, 54, 4, 2],
        [2, 55, 4, 2],
        [2, 56, 4, 2],
        [2, 57, 4, 2],
        [2, 58, 4, 2],
        [2, 59, 4, 2],
        [2, 60, 4, 2],
        [2, 61, 4, 2],
        [2, 62, 4, 2],
        [2, 63, 4, 2],
        [2, 64, 4, 2],
        [3, 2, 4, 2],
        [3, 3, 4, 2],
        [3, 4, 4, 2],
        [3, 5, 4, 2],
        [3, 6, 4, 2],
        [3, 7, 4, 2],
        [3, 8, 4, 2],
        [3, 9, 4, 2],
        [3, 10, 4, 2],
        [3, 11, 4, 2],
        [3, 12, 4, 2],
        [3, 13, 4, 2],
        [3, 14, 4, 2],
        [3, 15, 4, 2],
        [3, 16, 4, 2],
        [3, 17, 4, 2],
        [3, 18, 4, 2],
        [3, 19, 4, 2],
        [3, 20, 4, 2],
        [3, 21, 4, 2],
        [3, 22, 4, 2],
        [3, 23, 4, 2],
        [3, 24, 4, 2],
        [3, 25, 4, 2],
        [3, 26, 4, 2],
        [3, 27, 4, 2],
        [3, 28, 4, 2],
        [3, 29, 4, 2],
        [3, 30, 4, 2],
        [3, 31, 4, 2],
        [3, 32, 4, 2],
        [3, 33, 4, 2],
        [3, 34, 4, 2],
        [4, 2, 4, 2],
        [4, 3, 4, 2],
        [4, 4, 4, 2],
    ];

    /**
     * Returns random formula -> nice integer result guaranteed
     */
    public function getRandom(): CaptchaDto
    {
        $c = count(self::$precoputed);
        $rand = mt_rand(0, $c - 1);

        $pol = self::$precoputed[$rand];
        return new CaptchaDto($pol[0], $pol[1], $pol[3], $pol[2]);
    }

    /**
     * Checks integration result
     */
    public function isCorrect(CaptchaDto $dto, int $result): bool
    {
        $calculation = $this->solvePolynom(
            $dto->d3,
            $dto->d0,
            $dto->lowerLimit,
            $dto->upperLimit
        );

        return $calculation === $result;
    }

    // Solves definite integral
    // Polynom -> ax^3 + b
    // Integrate -> (a*(x^4 / 4)) + (b * x)
    private function solvePolynom(int $a, int $b, int $lower, int $upper): int
    {
        $p1 = ($a * (pow($upper, 4) / 4)) + ($b * $upper);
        $p2 = ($a * (pow($lower, 4) / 4)) + ($b * $lower);

        return intval($p1 - $p2);
    }
}
