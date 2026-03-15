<?php

declare(strict_types=1);

namespace App\Domain\Report;

use App\Domain\Report\ValueObject\Score;

final readonly class ScoringService
{
    public function __construct(
        private ScoringConfiguration $configuration,
    ) {
    }

    public function calculateScore(DeveloperStatistics $statistics): Score
    {
        return new Score(
            $statistics->mergeRequestsCreated->value * $this->configuration->mergeRequestCreated->value +
            $statistics->approvalsGiven->value * $this->configuration->approvalsGiven->value +
            $statistics->mergeRequestsMerged->value * $this->configuration->mergeRequestMerged->value +
            $statistics->mergeRequestsMergedWithApproval->value * $this->configuration->mergeRequestApproved->value +
            $statistics->mergeRequestsTested->value * $this->configuration->mergeRequestTested->value +
            $statistics->linesAdded->value * $this->configuration->linesAdded->value +
            $statistics->linesDeleted->value * $this->configuration->linesRemoved->value +
            $statistics->mergeRequestsSelfApproved->value * $this->configuration->selfApprovals->value +
            $statistics->commitsToDefaultBranch->value * $this->configuration->directCommitsToMain->value,
        );
    }
}
