<?php

namespace App\Domain\GitLab\Stats;

use App\Domain\GitLab\Stats\ValueObject\StatsAdditions;
use App\Domain\GitLab\Stats\ValueObject\StatsDeletions;
use App\Domain\GitLab\Stats\ValueObject\StatsTotal;

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
