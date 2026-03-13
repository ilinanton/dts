<?php

declare(strict_types=1);

namespace App\Domain\Report;

use App\Domain\Report\ValueObject\ScoringPenalty;
use App\Domain\Report\ValueObject\ScoringWeight;

final readonly class ScoringConfiguration
{
    public function __construct(
        public ScoringWeight $mergeRequestCreated,
        public ScoringWeight $approvalsGiven,
        public ScoringWeight $mergeRequestMerged,
        public ScoringWeight $mergeRequestApproved,
        public ScoringWeight $mergeRequestTested,
        public ScoringWeight $linesAdded,
        public ScoringWeight $linesRemoved,
        public ScoringPenalty $selfApprovals,
        public ScoringPenalty $directCommitsToMain,
    ) {
    }
}
