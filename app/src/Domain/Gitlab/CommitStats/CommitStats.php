<?php

namespace App\Domain\Gitlab\CommitStats;

use App\Domain\Common\AbstractEntity;
use App\Domain\Gitlab\CommitStats\ValueObject\CommitStatsAdditions;
use App\Domain\Gitlab\CommitStats\ValueObject\CommitStatsDeletions;
use App\Domain\Gitlab\CommitStats\ValueObject\CommitStatsFiles;
use App\Domain\Gitlab\CommitStats\ValueObject\CommitStatsId;
use App\Domain\Gitlab\CommitStats\ValueObject\CommitStatsProjectId;

final readonly class CommitStats extends AbstractEntity
{
    public function __construct(
        public CommitStatsId $id,
        public CommitStatsProjectId $projectId,
        public CommitStatsFiles $files,
        public CommitStatsAdditions $additions,
        public CommitStatsDeletions $deletions,
    ) {
    }
}
