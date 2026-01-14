<?php

declare(strict_types=1);

namespace App\Domain\Report;

final readonly class ScoringService
{
    public function __construct(
        private ScoringConfiguration $configuration,
    ) {
    }

    public function calculateScore(DeveloperStatistics $statistics): float
    {
        return
            $statistics->mergeRequestsCreated * $this->configuration->mergeRequestCreated +
            $statistics->mergeRequestsMerged * $this->configuration->mergeRequestMerged +
            $statistics->mergeRequestsMergedWithoutApproval * $this->configuration->mergedWithoutApproval +
            $statistics->mergeRequestsApproved * $this->configuration->approvalsGiven +
            $statistics->mergeRequestsSelfApproved * $this->configuration->selfApprovals +
            $statistics->commitsToDefaultBranch * $this->configuration->directCommitsToMain +
            $statistics->linesAdded * $this->configuration->linesAdded +
            $statistics->linesDeleted * $this->configuration->linesRemoved;
    }
}
