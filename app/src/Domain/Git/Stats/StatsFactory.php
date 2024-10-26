<?php

declare(strict_types=1);

namespace App\Domain\Git\Stats;

use App\Domain\Git\Stats\ValueObject\StatsAdditions;
use App\Domain\Git\Stats\ValueObject\StatsDeletions;
use App\Domain\Git\Stats\ValueObject\StatsFiles;

final class StatsFactory
{
    public function create(array $data): Stats
    {
        return new Stats(
            new StatsFiles($data['files'] ?? 0),
            new StatsAdditions($data['additions'] ?? 0),
            new StatsDeletions($data['deletions'] ?? 0),
        );
    }
}
