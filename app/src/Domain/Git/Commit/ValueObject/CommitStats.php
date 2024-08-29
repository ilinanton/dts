<?php

namespace App\Domain\Git\Commit\ValueObject;

use App\Domain\Git\Stats\Stats;
use App\Domain\Git\Stats\StatsFactory;

final class CommitStats
{
    private Stats $value;

    public function __construct(array $value)
    {
        $commitStatsFactory = new StatsFactory();
        $this->value = $commitStatsFactory->create($value);
    }

    public function getValue(): Stats
    {
        return $this->value;
    }
}
