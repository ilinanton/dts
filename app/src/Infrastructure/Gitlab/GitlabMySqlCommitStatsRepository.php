<?php

declare(strict_types=1);

namespace App\Infrastructure\Gitlab;

use App\Domain\Gitlab\CommitStats\CommitStats;
use App\Domain\Gitlab\CommitStats\Repository\GitlabStorageCommitStatsRepositoryInterface;
use PDO;

final readonly class GitlabMySqlCommitStatsRepository implements GitlabStorageCommitStatsRepositoryInterface
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
     author_email,
     author_date,
     files,
     additions,
     deletions
     )
VALUES
    (
     :GIT_COMMIT_ID,
     :PROJECT_ID,
     :AUTHOR_EMAIL,
     :AUTHOR_DATE,
     :FILES,
     :ADDITIONS,
     :DELETIONS
    )
ON DUPLICATE KEY UPDATE
    author_email = VALUES(author_email),
    author_date = VALUES(author_date)
SQL;

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':GIT_COMMIT_ID' => $object->gitCommitId->value,
            ':PROJECT_ID' => $object->projectId->value,
            ':AUTHOR_EMAIL' => $object->authorEmail->value,
            ':AUTHOR_DATE' => $object->authorDate->getValue(),
            ':FILES' => $object->files->value,
            ':ADDITIONS' => $object->additions->value,
            ':DELETIONS' => $object->deletions->value,
        ]);
    }
}
