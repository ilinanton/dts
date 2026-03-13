<?php

declare(strict_types=1);

namespace App\Domain\Report\ValueObject;

use InvalidArgumentException;

final readonly class ScoringWeight
{
    public function __construct(
        public float $value,
    ) {
        if (!is_finite($value) || $value < 0.0) {
            throw new InvalidArgumentException(
                'ScoringWeight must be a finite non-negative number, got ' . $value
            );
        }
    }
}
