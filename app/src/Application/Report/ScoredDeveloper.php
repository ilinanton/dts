<?php

declare(strict_types=1);

namespace App\Application\Report;

use App\Domain\Report\DeveloperStatistics;
use App\Domain\Report\ValueObject\Score;

final readonly class ScoredDeveloper
{
    public function __construct(
        public DeveloperStatistics $statistics,
        public Score $score,
    ) {
    }
}
