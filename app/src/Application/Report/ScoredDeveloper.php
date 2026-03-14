<?php

declare(strict_types=1);

namespace App\Application\Report;

use App\Domain\Report\DeveloperStatistics;

final readonly class ScoredDeveloper
{
    public function __construct(
        public DeveloperStatistics $statistics,
        public float $score,
    ) {
    }
}
