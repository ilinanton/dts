<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\CommitStats;

use App\Domain\Common\EntityInterface;
use App\Domain\Git\Stats\Stats;
use App\Domain\Gitlab\CommitStats\ValueObject\CommitStatsGitCommitId;
use App\Domain\Gitlab\CommitStats\ValueObject\CommitStatsProjectId;

final readonly class CommitStats implements EntityInterface
{
    public function __construct(
        public CommitStatsGitCommitId $gitCommitId,
        public CommitStatsProjectId $projectId,
        public Stats $stats,
    ) {
    }
}
