<?php

declare(strict_types=1);

namespace App\Application\Report;

use App\Application\UseCaseInterface;
use DateInterval;
use DateTime;
use PDO;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableCell;
use Symfony\Component\Console\Helper\TableCellStyle;
use Symfony\Component\Console\Output\ConsoleOutput;

final readonly class DevReportUseCase implements UseCaseInterface
{
    public function __construct(
        private PDO $pdo,
    ) {
    }

    public function execute(): void
    {
        $afterAt = $this->getDate()->format('Y-m-d') . ' 00:00:00';
        $data = $this->getData($afterAt);
        $this->printTable($data);
    }

    private function printTable(array $rows): void
    {
        $output = new ConsoleOutput();
        $table = new Table($output);
        $table->setStyle('markdown');
        $table
            ->setHeaders([
                [
                    new TableCell('User', ['colspan' => 2]),
                    new TableCell('Merge request', ['colspan' => 2]),
                    new TableCell('Merge request merged', ['colspan' => 3]),
                    new TableCell('Commit'),
                    new TableCell('Lines of code', ['colspan' => 3]),
                ],
                [
                    'id',
                    'name',
                    'approved',
                    'created',
                    new TableCell(
                        'without approv',
                        [
                            'colspan' => 1,
                            'style' => new TableCellStyle([
                                'bg' => 'red',
                            ]),
                        ],
                    ),
                    new TableCell(
                        'self approved',
                        [
                            'colspan' => 1,
                            'style' => new TableCellStyle([
                                'bg' => 'blue',
                            ]),
                        ],
                    ),
                    'total',
                    new TableCell(
                        'to def branch',
                        [
                            'colspan' => 1,
                            'style' => new TableCellStyle([
                                'bg' => 'blue',
                            ]),
                        ],
                    ),
                    'add',
                    'del',
                    'total',
                    'score',
                ],
            ])
            ->setRows($rows);
        $table->render();
    }

    private function getData(string $afterAt): array
    {
        $sql = <<<SQL
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
        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(':AFTER_AT', $afterAt);
        $stmt->bindValue(':POINTS_MERGE_REQUEST_CREATED', (float)$_ENV['POINTS_MERGE_REQUEST_CREATED']);
        $stmt->bindValue(':POINTS_MERGE_REQUEST_MERGED', (float)$_ENV['POINTS_MERGE_REQUEST_MERGED']);
        $stmt->bindValue(':PENALTY_MERGED_WITHOUT_APPROVAL', (float)$_ENV['PENALTY_MERGED_WITHOUT_APPROVAL']);
        $stmt->bindValue(':POINTS_SELF_APPROVALS', (float)$_ENV['POINTS_SELF_APPROVALS']);
        $stmt->bindValue(':POINTS_APPROVALS_GIVEN', (float)$_ENV['POINTS_APPROVALS_GIVEN']);
        $stmt->bindValue(':POINTS_DIRECT_COMMITS_TO_MAIN', (float)$_ENV['POINTS_DIRECT_COMMITS_TO_MAIN']);
        $stmt->bindValue(':POINTS_LINES_ADDED', (float)$_ENV['POINTS_LINES_ADDED']);
        $stmt->bindValue(':POINTS_LINES_REMOVED', (float)$_ENV['POINTS_LINES_REMOVED']);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function getDate(): DateTime
    {
        $defaultDate = new DateTime();
        $defaultDate->sub(new DateInterval('P2W'));
        $input = trim(readline('Enter the start date for the report (YYYY-MM-DD): '));
        $inputDateTime = DateTime::createFromFormat('Y-m-d', $input);
        if ($inputDateTime === false) {
            echo $defaultDate->format('Y-m-d') . PHP_EOL;
            return $defaultDate;
        }
        return $inputDateTime;
    }
}
