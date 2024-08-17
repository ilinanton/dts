<?php

namespace App\Domain\GitLab\Commit\ValueObject;

use App\Domain\GitLab\Stats\Stats;
use App\Domain\GitLab\Stats\StatsFactory;

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
