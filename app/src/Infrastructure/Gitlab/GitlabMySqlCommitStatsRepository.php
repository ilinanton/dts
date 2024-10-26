<?php

declare(strict_types=1);

namespace App\Infrastructure\Gitlab;

use App\Domain\Gitlab\CommitStats\CommitStats;
use App\Domain\Gitlab\CommitStats\Repository\GitlabDataBaseCommitStatsRepositoryInterface;
use PDO;

final readonly class GitlabMySqlCommitStatsRepository implements GitlabDataBaseCommitStatsRepositoryInterface
{
    public function __construct(
        private PDO $pdo,
    ) {
    }

    public function save(CommitStats $object): void
    {
        $sql = <<<SQL
INSERT INTO gitlab_commit_stats
    (
     git_commit_id,
     project_id,
     files,
     additions,
     deletions
     )
VALUES
    (
     :GIT_COMMIT_ID,
     :PROJECT_ID,
     :FILES,
     :ADDITIONS,
     :DELETIONS
    )
ON DUPLICATE KEY UPDATE git_commit_id = git_commit_id
SQL;

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':GIT_COMMIT_ID' => $object->gitCommitId->value,
            ':PROJECT_ID' => $object->projectId->value,
            ':FILES' => $object->files->value,
            ':ADDITIONS' => $object->additions->value,
            ':DELETIONS' => $object->deletions->value,
        ]);
    }
}
