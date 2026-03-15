<?php

declare(strict_types=1);

namespace App\Infrastructure\Report;

use App\Domain\Report\DeveloperStatistics;
use App\Domain\Report\DeveloperStatisticsCollection;
use App\Domain\Report\ReportCriteria;
use App\Domain\Report\Repository\DevReportRepositoryInterface;
use App\Domain\Report\ValueObject\ApprovalCount;
use App\Domain\Report\ValueObject\CommitCount;
use App\Domain\Report\ValueObject\DeveloperUserId;
use App\Domain\Report\ValueObject\DeveloperUserName;
use App\Domain\Report\ValueObject\LabelNameCollection;
use App\Domain\Report\ValueObject\LineCount;
use App\Domain\Report\ValueObject\MergeRequestCount;
use PDO;

final readonly class DevReportMySqlRepository implements DevReportRepositoryInterface
{
    public function __construct(
        private PDO $pdo,
    ) {
    }

    public function getStatistics(ReportCriteria $criteria): DeveloperStatisticsCollection
    {
        $afterAt = $criteria->startDate->getValue();
        $hasTestedLabels = !$criteria->testedLabelNames->isEmpty();

        $testedJoin = '';
        $testedSelect = '0 AS mr_tested';
        if ($hasTestedLabels) {
            $testedSelect = 'COALESCE(tested_stats.mr_tested, 0) AS mr_tested';
            $placeholders = $this->buildLabelPlaceholders($criteria->testedLabelNames);
            $testedJoin = <<<SQL
LEFT JOIN (
    SELECT
        mr.author_id,
        COUNT(DISTINCT rle.resource_id) AS mr_tested
    FROM gitlab_resource_label_event rle
    INNER JOIN gitlab_label l ON l.id = rle.label_id
    INNER JOIN gitlab_merge_request mr ON mr.id = rle.resource_id
    INNER JOIN gitlab_project p ON p.id = mr.project_id
    WHERE rle.resource_type = 'MergeRequest'
      AND rle.action_name = 'add'
      AND l.name IN ({$placeholders})
      AND mr.state = 'merged'
      AND mr.merged_at >= :AFTER_AT
      AND mr.target_branch = p.default_branch
      AND NOT EXISTS (
          SELECT 1
          FROM gitlab_resource_label_event rle2
          WHERE rle2.resource_id = rle.resource_id
            AND rle2.label_id = rle.label_id
            AND rle2.action_name = 'remove'
            AND rle2.created_at > rle.created_at
      )
    GROUP BY mr.author_id
) tested_stats ON tested_stats.author_id = u.id
SQL;
        }

        $sql = <<<SQL
SELECT
    u.id,
    u.name AS user,
    COALESCE(mr_stats.mr_created, 0) AS mr_created,
    COALESCE(ev_stats.mr_approved, 0) AS approvals_given,
    COALESCE(mr_stats.mr_merged, 0) AS mr_merged,
    COALESCE(mr_stats.mr_merged_with_approval, 0) AS mr_merged_with_approval,
    COALESCE(ev_stats.mr_self_approved, 0) AS mr_self_approved,
    COALESCE(ev_stats.committed_to_default_branch, 0) AS committed_to_default_branch,
    COALESCE(commit_stats.loc_add, 0) AS loc_add,
    COALESCE(commit_stats.loc_del, 0) AS loc_del,
    {$testedSelect}
FROM gitlab_user u
LEFT JOIN (
    SELECT
        mr.author_id,
        COUNT(CASE WHEN mr.created_at >= :AFTER_AT AND mr.source_branch <> p.default_branch THEN 1 END) AS mr_created,
        COUNT(CASE WHEN mr.state = 'merged' AND mr.merged_at >= :AFTER_AT AND mr.target_branch = p.default_branch THEN 1 END) AS mr_merged,
        COUNT(CASE WHEN mr.state = 'merged' AND mr.merged_at >= :AFTER_AT AND mr.target_branch = p.default_branch
            AND EXISTS (
                SELECT 1 FROM gitlab_event ea
                WHERE ea.target_id = mr.id
                  AND ea.action_name = 'approved'
                  AND NOT EXISTS (
                      SELECT 1 FROM gitlab_event eu
                      WHERE eu.target_id = ea.target_id
                        AND eu.author_id = ea.author_id
                        AND eu.action_name = 'unapproved'
                        AND eu.created_at > ea.created_at
                  )
            )
        THEN 1 END) AS mr_merged_with_approval
    FROM gitlab_merge_request mr
    INNER JOIN gitlab_project p ON p.id = mr.project_id
    WHERE mr.created_at >= :AFTER_AT OR mr.merged_at >= :AFTER_AT
    GROUP BY mr.author_id
) mr_stats ON mr_stats.author_id = u.id
LEFT JOIN (
    SELECT
        e.author_id,
        COUNT(CASE WHEN e.action_name = 'approved' THEN 1 END) AS mr_approved,
        COUNT(CASE WHEN e.action_name = 'approved' AND mr.author_id = e.author_id THEN 1 END) AS mr_self_approved,
        COUNT(CASE WHEN e.action_name = 'pushed to'
                    AND e.push_data_ref_type = 'branch'
                    AND p.default_branch = e.push_data_ref
                    AND e.push_data_commit_title NOT LIKE 'Merge branch%'
              THEN 1 END
        ) AS committed_to_default_branch
    FROM gitlab_event e
    LEFT JOIN gitlab_merge_request mr
           ON mr.id = e.target_id
    LEFT JOIN gitlab_project p
           ON p.id = e.project_id
    WHERE e.created_at >= :AFTER_AT
    GROUP BY e.author_id
) ev_stats ON ev_stats.author_id = u.id
LEFT JOIN (
    SELECT
        x.gitlab_user_id,
        SUM(s.additions) AS loc_add,
        SUM(s.deletions) AS loc_del
    FROM gitlab_user_x_git_user x
    INNER JOIN gitlab_commit c
            ON c.author_email = x.git_email
           AND c.created_at >= :AFTER_AT
    INNER JOIN gitlab_commit_stats s
            ON s.project_id = c.project_id
           AND s.git_commit_id = c.git_commit_id
    GROUP BY x.gitlab_user_id
) commit_stats ON commit_stats.gitlab_user_id = u.id
{$testedJoin}
WHERE u.state = 'active'
SQL;

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':AFTER_AT', $afterAt);

        if ($hasTestedLabels) {
            $this->bindLabelValues($stmt, $criteria->testedLabelNames);
        }

        $stmt->execute();

        $collection = new DeveloperStatisticsCollection();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $collection->add(new DeveloperStatistics(
                userId: new DeveloperUserId((int)$row['id']),
                userName: new DeveloperUserName((string)$row['user']),
                mergeRequestsCreated: new MergeRequestCount((int)$row['mr_created']),
                approvalsGiven: new ApprovalCount((int)$row['approvals_given']),
                mergeRequestsMerged: new MergeRequestCount((int)$row['mr_merged']),
                mergeRequestsMergedWithApproval: new MergeRequestCount((int)$row['mr_merged_with_approval']),
                mergeRequestsTested: new MergeRequestCount((int)$row['mr_tested']),
                linesAdded: new LineCount((int)$row['loc_add']),
                linesDeleted: new LineCount((int)$row['loc_del']),
                mergeRequestsSelfApproved: new MergeRequestCount((int)$row['mr_self_approved']),
                commitsToDefaultBranch: new CommitCount((int)$row['committed_to_default_branch']),
            ));
        }

        return $collection;
    }

    private function buildLabelPlaceholders(LabelNameCollection $labels): string
    {
        $keys = [];
        foreach (array_keys(iterator_to_array($labels)) as $i) {
            $keys[] = ':LABEL_' . $i;
        }
        return implode(', ', $keys);
    }

    private function bindLabelValues(\PDOStatement $stmt, LabelNameCollection $labels): void
    {
        foreach (array_values(iterator_to_array($labels)) as $i => $label) {
            $stmt->bindValue(':LABEL_' . $i, $label->value);
        }
    }
}
