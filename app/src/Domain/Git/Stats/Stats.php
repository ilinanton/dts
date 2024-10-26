<?php

declare(strict_types=1);

namespace App\Domain\Git\Stats;

use App\Domain\Git\Stats\ValueObject\StatsAdditions;
use App\Domain\Git\Stats\ValueObject\StatsDeletions;
use App\Domain\Git\Stats\ValueObject\StatsFiles;
use App\Domain\Common\AbstractEntity;

final readonly class Stats extends AbstractEntity
{
    public function __construct(
        public StatsFiles $files,
        public StatsAdditions $additions,
        public StatsDeletions $deletions,
    ) {
    }
}
