<?php

declare(strict_types=1);

namespace App\Domain\Report;

use App\Domain\Report\ValueObject\ApprovalCount;
use App\Domain\Report\ValueObject\CommitCount;
use App\Domain\Report\ValueObject\DeveloperUserId;
use App\Domain\Report\ValueObject\DeveloperUserName;
use App\Domain\Report\ValueObject\LineCount;
use App\Domain\Report\ValueObject\MergeRequestCount;

final readonly class DeveloperStatistics
{
    public function __construct(
        public DeveloperUserId $userId,
        public DeveloperUserName $userName,
        public MergeRequestCount $mergeRequestsCreated,
        public ApprovalCount $approvalsGiven,
        public MergeRequestCount $mergeRequestsMerged,
        public MergeRequestCount $mergeRequestsMergedWithApproval,
        public MergeRequestCount $mergeRequestsTested,
        public LineCount $linesAdded,
        public LineCount $linesDeleted,
        public MergeRequestCount $mergeRequestsSelfApproved,
        public CommitCount $commitsToDefaultBranch,
    ) {
    }

    public function getTotalLinesChanged(): int
    {
        return $this->linesAdded->value + $this->linesDeleted->value;
    }
}
