<?php

namespace App\Domain\Gitlab\Stats;

use App\Domain\Gitlab\Stats\ValueObject\StatsAdditions;
use App\Domain\Gitlab\Stats\ValueObject\StatsDeletions;
use App\Domain\Gitlab\Stats\ValueObject\StatsTotal;

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
