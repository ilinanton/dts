<?php

namespace App\Domain\Gitlab\Stats;

use App\Domain\Common\AbstractEntity;
use App\Domain\Gitlab\Stats\ValueObject\StatsAdditions;
use App\Domain\Gitlab\Stats\ValueObject\StatsDeletions;
use App\Domain\Gitlab\Stats\ValueObject\StatsTotal;

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
