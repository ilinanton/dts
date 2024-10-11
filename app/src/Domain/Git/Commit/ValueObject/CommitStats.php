<?php

namespace App\Domain\Git\Commit\ValueObject;

use App\Domain\Git\Stats\Stats;
use App\Domain\Git\Stats\StatsFactory;

final readonly class CommitStats
{
    public Stats $value;

    public function __construct(array $value)
    {
        $commitStatsFactory = new StatsFactory();
        $this->value = $commitStatsFactory->create($value);
    }
}
