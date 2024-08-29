<?php

namespace App\Domain\Gitlab\CommitStats;

use App\Domain\Gitlab\CommitStats\ValueObject\StatsAdditions;
use App\Domain\Gitlab\CommitStats\ValueObject\StatsDeletions;
use App\Domain\Gitlab\CommitStats\ValueObject\StatsTotal;

final class StatsFactory
{
    public function create(array $data): Stats
    {
        return new Stats(
            new StatsAdditions($data['additions'] ?? 0),
            new StatsDeletions($data['deletions'] ?? 0),
            new StatsTotal($data['total'] ?? 0),
        );
    }
}
