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
            $statistics->mergeRequestsCreated * $this->configuration->mergeRequestCreated->value +
            $statistics->approvalsGiven * $this->configuration->approvalsGiven->value +
            $statistics->mergeRequestsMerged * $this->configuration->mergeRequestMerged->value +
            $statistics->mergeRequestsMergedWithApproval * $this->configuration->mergeRequestApproved->value +
            $statistics->mergeRequestsTested * $this->configuration->mergeRequestTested->value +
            $statistics->linesAdded * $this->configuration->linesAdded->value +
            $statistics->linesDeleted * $this->configuration->linesRemoved->value +
            $statistics->mergeRequestsSelfApproved * $this->configuration->selfApprovals->value +
            $statistics->commitsToDefaultBranch * $this->configuration->directCommitsToMain->value;
    }
}
