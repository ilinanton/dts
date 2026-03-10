<?php

declare(strict_types=1);

namespace App\Domain\Report;

final readonly class ScoringConfiguration
{
    public function __construct(
        public float $mergeRequestCreated,
        public float $approvalsGiven,
        public float $mergeRequestMerged,
        public float $mergeRequestApproved,
        public float $mergeRequestTested,
        public float $linesAdded,
        public float $linesRemoved,
        public float $selfApprovals,
        public float $directCommitsToMain,
    ) {
    }
}
