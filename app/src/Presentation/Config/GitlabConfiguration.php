<?php

declare(strict_types=1);

namespace App\Presentation\Config;

final readonly class GitlabConfiguration
{
    /** @var list<int> */
    public array $excludedProjectIds;

    /** @var list<int> */
    public array $excludedUserIds;

    /** @var list<string> */
    public array $gitLogExcludePath;

    /** @var list<string> */
    public array $reportTestedLabels;

    /**
     * @param list<int> $excludedProjectIds
     * @param list<int> $excludedUserIds
     * @param list<string> $gitLogExcludePath
     * @param list<string> $reportTestedLabels
     */
    public function __construct(
        public string $gitlabUrl,
        public string $gitlabToken,
        public int $gitlabGroupId,
        public string $syncDateAfter,
        array $excludedProjectIds,
        array $excludedUserIds,
        array $gitLogExcludePath,
        public float $pointsMergeRequestCreated,
        public float $pointsApprovalsGiven,
        public float $pointsMergeRequestMerged,
        public float $pointsMergeRequestApproved,
        public float $pointsMergeRequestTested,
        public float $pointsLinesAdded,
        public float $pointsLinesRemoved,
        public float $pointsSelfApprovals,
        public float $pointsDirectCommitsToMain,
        array $reportTestedLabels,
    ) {
        $this->excludedProjectIds = $excludedProjectIds;
        $this->excludedUserIds = $excludedUserIds;
        $this->gitLogExcludePath = $gitLogExcludePath;
        $this->reportTestedLabels = $reportTestedLabels;
    }

    /** @param array<string, mixed> $env */
    public static function fromEnv(array $env): self
    {
        return new self(
            gitlabUrl: (string)$env['GITLAB_URL'],
            gitlabToken: (string)$env['GITLAB_TOKEN'],
            gitlabGroupId: (int)$env['GITLAB_GROUP_ID'],
            syncDateAfter: (string)$env['GITLAB_SYNC_DATE_AFTER'],
            excludedProjectIds: self::parseIntList((string)$env['GITLAB_EXCLUDED_PROJECT_IDS']),
            excludedUserIds: self::parseIntList((string)$env['GITLAB_EXCLUDED_USER_IDS']),
            gitLogExcludePath: self::parseStringList((string)$env['GIT_LOG_EXCLUDE_PATH']),
            pointsMergeRequestCreated: (float)$env['POINTS_MERGE_REQUEST_CREATED'],
            pointsApprovalsGiven: (float)$env['POINTS_APPROVALS_GIVEN'],
            pointsMergeRequestMerged: (float)$env['POINTS_MERGE_REQUEST_MERGED'],
            pointsMergeRequestApproved: (float)$env['POINTS_MERGE_REQUEST_APPROVED'],
            pointsMergeRequestTested: (float)$env['POINTS_MERGE_REQUEST_TESTED'],
            pointsLinesAdded: (float)$env['POINTS_LINES_ADDED'],
            pointsLinesRemoved: (float)$env['POINTS_LINES_REMOVED'],
            pointsSelfApprovals: (float)$env['POINTS_SELF_APPROVALS'],
            pointsDirectCommitsToMain: (float)$env['POINTS_DIRECT_COMMITS_TO_MAIN'],
            reportTestedLabels: self::parseStringList((string)($env['REPORT_TESTED_LABELS'] ?? '')),
        );
    }

    /** @return list<int> */
    private static function parseIntList(string $value): array
    {
        if (trim($value) === '') {
            return [];
        }

        return array_map(
            static fn(string $item): int => (int)trim($item),
            explode(',', $value),
        );
    }

    /** @return list<string> */
    private static function parseStringList(string $value): array
    {
        if (trim($value) === '') {
            return [];
        }

        return array_map(
            static fn(string $item): string => trim($item),
            explode(',', $value),
        );
    }
}
