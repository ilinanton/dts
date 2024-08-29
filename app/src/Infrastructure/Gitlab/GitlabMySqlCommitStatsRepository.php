<?php

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
     id,
     project_id,
     files,
     additions,
     deletions
     )
VALUES
    (
     :ID,
     :PROJECT_ID,
     :FILES,
     :ADDITIONS,
     :DELETIONS
    )
ON DUPLICATE KEY UPDATE id = id
SQL;

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':ID' => $object->id->getValue(),
            ':PROJECT_ID' => $object->projectId->getValue(),
            ':FILES' => $object->files->getValue(),
            ':ADDITIONS' => $object->additions->getValue(),
            ':DELETIONS' => $object->deletions->getValue(),
        ]);
    }
}
