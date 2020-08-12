<?php

declare(strict_types=1);

namespace Kozak\Tomas\App\Model;

final class CaptchaDto
{
    /** Polynom degree 0 */
    public int $d0;

    /** Polynom degree 3 */
    public int $d3;

    /** Integral upper limit */
    public int $upperLimit;

    /** Integral lower limit */
    public int $lowerLimit;

    public function __construct(int $d0, int $d3, int $lowerLimit, int $upperLimit)
    {
        $this->d0 = $d0;
        $this->d3 = $d3;
        $this->upperLimit = $upperLimit;
        $this->lowerLimit = $lowerLimit;
    }

    public function serialize(): string
    {
        return
            $this->d3 . '|' .
            $this->d0 . '|' .
            $this->lowerLimit . '|' .
            $this->upperLimit;
    }

    public static function deserialize(string $serialized): self
    {
        $parts = explode('|', $serialized);
        if (\count($parts) !== 4) {
            throw new CaptchaException('Could not deserialize Dto object from string');
        }

        return new self(
            (int) $parts[1],
            (int) $parts[0],
            (int) $parts[2],
            (int) $parts[3],
        );
    }
}
