<?php

namespace App\Domain\Gitlab\CommitStats;

use App\Domain\Gitlab\CommitStats\ValueObject\CommitStatsAdditions;
use App\Domain\Gitlab\CommitStats\ValueObject\CommitStatsDeletions;
use App\Domain\Gitlab\CommitStats\ValueObject\CommitStatsFiles;
use App\Domain\Gitlab\CommitStats\ValueObject\CommitStatsId;
use App\Domain\Gitlab\CommitStats\ValueObject\CommitStatsProjectId;

final class CommitStatsFactory
{
    public function create(int $projectId, array $data): CommitStats
    {
        return new CommitStats(
            new CommitStatsId($data['additions'] ?? 0),
            new CommitStatsProjectId($projectId),
            new CommitStatsFiles($data['additions'] ?? 0),
            new CommitStatsAdditions($data['additions'] ?? 0),
            new CommitStatsDeletions($data['deletions'] ?? 0),
        );
    }
}
