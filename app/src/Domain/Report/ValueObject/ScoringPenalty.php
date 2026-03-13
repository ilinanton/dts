<?php

declare(strict_types=1);

namespace App\Domain\Report\ValueObject;

use InvalidArgumentException;

final readonly class ScoringPenalty
{
    public function __construct(
        public float $value,
    ) {
        if (!is_finite($value) || $value > 0.0) {
            throw new InvalidArgumentException(
                'ScoringPenalty must be a finite non-positive number, got ' . $value
            );
        }
    }
}
