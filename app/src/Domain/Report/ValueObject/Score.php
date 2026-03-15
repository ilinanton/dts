<?php

declare(strict_types=1);

namespace App\Domain\Report\ValueObject;

final readonly class Score
{
    public function __construct(
        public float $value,
    ) {
    }

    public function formatted(): string
    {
        return number_format($this->value, 2, '.', '');
    }
}
