<?php

declare(strict_types=1);

namespace App\Infrastructure\Report;

use App\Domain\Report\Repository\DevReportDataBaseRepositoryInterface;
use PDO;

final readonly class DevReportMySqlRepository implements DevReportDataBaseRepositoryInterface
{
    public function __construct(
        private PDO $pdo,
    ) {
    }

    public function getData(): array
    {
        $stmt = $this->pdo->prepare($this->getSql());
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $userFactory = new UserCollectionFromArray($data);
        return $userFactory->create();
    }

    private function getSql(): string
    {
        return <<<SQL
SELECT
    u.id,
    u.name AS user,
    COALESCE(ev_stats.mr_approved, 0) AS mr_approved,
    COALESCE(mr_stats.mr_created, 0) AS mr_created,
    COALESCE(mr_stats.mr_merged_without_approv, 0) AS mr_merged_without_approv,
    COALESCE(ev_stats.mr_self_approved, 0) AS mr_self_approved,
    COALESCE(mr_stats.mr_merged, 0) AS mr_merged,
    COALESCE(ev_stats.committed_to_default_branch, 0) AS committed_to_default_branch,
    COALESCE(commit_stats.loc_add, 0) AS loc_add,
    COALESCE(commit_stats.loc_del, 0) AS loc_del,
    (COALESCE(commit_stats.loc_add, 0) + COALESCE(commit_stats.loc_del, 0)) AS total_loc,
    (
        COALESCE(mr_stats.mr_created, 0) * :POINTS_MERGE_REQUEST_CREATED +
        COALESCE(mr_stats.mr_merged, 0) * :POINTS_MERGE_REQUEST_MERGED +
        COALESCE(mr_stats.mr_merged_without_approv, 0) * :PENALTY_MERGED_WITHOUT_APPROVAL +
        COALESCE(ev_stats.mr_approved, 0) * :POINTS_APPROVALS_GIVEN +
        COALESCE(ev_stats.mr_self_approved, 0) * :POINTS_SELF_APPROVALS +
        COALESCE(ev_stats.committed_to_default_branch, 0) * :POINTS_DIRECT_COMMITS_TO_MAIN +
        COALESCE(commit_stats.loc_add, 0) * :POINTS_LINES_ADDED +
        COALESCE(commit_stats.loc_del, 0) * :POINTS_LINES_REMOVED
    ) AS score
FROM gitlab_user u
LEFT JOIN (
    SELECT
        mr.author_id,
        COUNT(CASE WHEN mr.created_at >= :AFTER_AT AND mr.source_branch <> p.default_branch THEN 1 END) AS mr_created,
        COUNT(CASE WHEN mr.state = 'merged' AND mr.merged_at >= :AFTER_AT AND mr.source_branch <> p.default_branch THEN 1 END) AS mr_merged,
        COUNT(CASE WHEN mr.state = 'merged' AND mr.merged_at >= :AFTER_AT AND mr.target_branch = p.default_branch AND e.id IS NULL THEN 1 END) AS mr_merged_without_approv
    FROM gitlab_merge_request mr
    INNER JOIN gitlab_project p ON p.id = mr.project_id
    LEFT JOIN gitlab_event e
           ON e.target_id = mr.id
          AND e.action_name = 'approved'
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
ORDER BY score DESC
SQL;
    }
}
