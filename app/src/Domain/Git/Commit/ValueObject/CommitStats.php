<?php

declare(strict_types=1);

namespace App\Domain\Git\Commit\ValueObject;

use App\Domain\Git\Stats\Stats;

final readonly class CommitStats
{
    public function __construct(
        public Stats $value,
    ) {
    }
}
