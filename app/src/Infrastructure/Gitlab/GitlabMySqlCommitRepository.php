<?php

declare(strict_types=1);

namespace App\Infrastructure\Gitlab;

use App\Domain\Gitlab\Commit\Commit;
use App\Domain\Gitlab\Commit\Repository\GitlabStorageCommitRepositoryInterface;
use PDO;

final readonly class GitlabMySqlCommitRepository implements GitlabStorageCommitRepositoryInterface
{
    public function __construct(
        private PDO $pdo,
    ) {
    }

    public function save(Commit $object): void
    {
        $sql = <<<SQL
INSERT INTO gitlab_commit
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
