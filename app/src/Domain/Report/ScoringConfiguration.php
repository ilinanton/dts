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
        $this->assertNonNegativeWeight('mergeRequestCreated', $mergeRequestCreated);
        $this->assertNonNegativeWeight('approvalsGiven', $approvalsGiven);
        $this->assertNonNegativeWeight('mergeRequestMerged', $mergeRequestMerged);
        $this->assertNonNegativeWeight('mergeRequestApproved', $mergeRequestApproved);
        $this->assertNonNegativeWeight('mergeRequestTested', $mergeRequestTested);
        $this->assertNonNegativeWeight('linesAdded', $linesAdded);
        $this->assertNonNegativeWeight('linesRemoved', $linesRemoved);
        $this->assertNonPositiveWeight('selfApprovals', $selfApprovals);
        $this->assertNonPositiveWeight('directCommitsToMain', $directCommitsToMain);
    }

    private function assertNonNegativeWeight(string $name, float $value): void
    {
        if (!is_finite($value) || $value < 0.0) {
            throw new \InvalidArgumentException(
                'Weight "' . $name . '" must be a finite non-negative number, got ' . $value
            );
        }
    }

    private function assertNonPositiveWeight(string $name, float $value): void
    {
        if (!is_finite($value) || $value > 0.0) {
            throw new \InvalidArgumentException(
                'Penalty "' . $name . '" must be a finite non-positive number, got ' . $value
            );
        }
    }
}
