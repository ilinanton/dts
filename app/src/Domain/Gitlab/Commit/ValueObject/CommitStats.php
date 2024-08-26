<?php

namespace App\Domain\Gitlab\Commit\ValueObject;

use App\Domain\Gitlab\Stats\Stats;
use App\Domain\Gitlab\Stats\StatsFactory;

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
