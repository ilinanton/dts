<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\CommitStats;

use App\Domain\Gitlab\CommitStats\ValueObject\CommitStatsAdditions;
use App\Domain\Gitlab\CommitStats\ValueObject\CommitStatsDeletions;
use App\Domain\Gitlab\CommitStats\ValueObject\CommitStatsFiles;
use App\Domain\Gitlab\CommitStats\ValueObject\CommitStatsGitCommitId;
use App\Domain\Gitlab\CommitStats\ValueObject\CommitStatsProjectId;

final readonly class CommitStatsFactory
{
    public function create(
        int $projectId,
        string $gitCommitId,
        int $files,
        int $additions,
        int $deletions,
    ): CommitStats {
        return new CommitStats(
            new CommitStatsGitCommitId($gitCommitId),
            new CommitStatsProjectId($projectId),
            new CommitStatsFiles($files),
            new CommitStatsAdditions($additions),
            new CommitStatsDeletions($deletions),
        );
    }
}
