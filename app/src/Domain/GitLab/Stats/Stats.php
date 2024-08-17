<?php

namespace App\Domain\GitLab\Stats;

use App\Domain\GitLab\Stats\ValueObject\StatsAdditions;
use App\Domain\GitLab\Stats\ValueObject\StatsDeletions;
use App\Domain\GitLab\Stats\ValueObject\StatsTotal;
use App\Domain\GitLab\Common\AbstractEntity;

final readonly class Stats extends AbstractEntity
{
    public function __construct(
        private StatsAdditions $additions,
        private StatsDeletions $deletions,
        private StatsTotal $total,
    ) {
    }

    public function getAdditions(): StatsAdditions
    {
        return $this->additions;
    }

    public function getDeletions(): StatsDeletions
    {
        return $this->deletions;
    }

    public function getTotal(): StatsTotal
    {
        return $this->total;
    }
}
