<?php

declare(strict_types=1);

namespace App\Domain\Report;

use App\Domain\Report\ValueObject\DeveloperUserId;
use App\Domain\Report\ValueObject\DeveloperUserName;

final readonly class DeveloperStatistics
{
    public function __construct(
        public DeveloperUserId $userId,
        public DeveloperUserName $userName,
        public int $mergeRequestsCreated,
        public int $approvalsGiven,
        public int $mergeRequestsMerged,
        public int $mergeRequestsMergedWithApproval,
        public int $mergeRequestsTested,
        public int $linesAdded,
        public int $linesDeleted,
        public int $mergeRequestsSelfApproved,
        public int $commitsToDefaultBranch,
    ) {
    }

    public function getTotalLinesChanged(): int
    {
        return $this->linesAdded + $this->linesDeleted;
    }
}
