<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\CommitStats;

use App\Domain\Git\Stats\Stats;
use App\Domain\Gitlab\CommitStats\ValueObject\CommitStatsGitCommitId;
use App\Domain\Gitlab\CommitStats\ValueObject\CommitStatsProjectId;

final readonly class CommitStatsFactory
{
    public function create(int $projectId, string $gitCommitId, Stats $stats): CommitStats
    {
        return new CommitStats(
            new CommitStatsGitCommitId($gitCommitId),
            new CommitStatsProjectId($projectId),
            $stats,
        );
    }
}
