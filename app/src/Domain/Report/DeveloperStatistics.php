<?php

declare(strict_types=1);

namespace App\Domain\Report;

final readonly class DeveloperStatistics
{
    public function __construct(
        public int $userId,
        public string $userName,
        public int $mergeRequestsApproved,
        public int $mergeRequestsCreated,
        public int $mergeRequestsMergedWithoutApproval,
        public int $mergeRequestsSelfApproved,
        public int $mergeRequestsMerged,
        public int $commitsToDefaultBranch,
        public int $linesAdded,
        public int $linesDeleted,
    ) {
    }

    public function getTotalLinesChanged(): int
    {
        return $this->linesAdded + $this->linesDeleted;
    }
}
