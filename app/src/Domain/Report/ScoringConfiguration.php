<?php

declare(strict_types=1);

namespace App\Domain\Report;

final readonly class ScoringConfiguration
{
    public function __construct(
        public float $mergeRequestCreated,
        public float $mergeRequestMerged,
        public float $mergedWithoutApproval,
        public float $selfApprovals,
        public float $approvalsGiven,
        public float $directCommitsToMain,
        public float $linesAdded,
        public float $linesRemoved,
    ) {
    }
}
