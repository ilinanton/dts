<?php

namespace App\Domain\Gitlab\CommitStats;

use App\Domain\Common\AbstractEntity;
use App\Domain\Gitlab\CommitStats\ValueObject\StatsAdditions;
use App\Domain\Gitlab\CommitStats\ValueObject\StatsDeletions;
use App\Domain\Gitlab\CommitStats\ValueObject\StatsFiles;

final readonly class Stats extends AbstractEntity
{
    public function __construct(
        public StatsFiles $files,
        public StatsAdditions $additions,
        public StatsDeletions $deletions,
    ) {
    }
}
