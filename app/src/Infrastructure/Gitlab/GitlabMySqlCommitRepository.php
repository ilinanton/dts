<?php

namespace App\Infrastructure\Gitlab;

use App\Domain\Gitlab\Commit\Commit;
use App\Domain\Gitlab\Commit\Repository\GitlabDataBaseCommitRepositoryInterface;
use PDO;

final readonly class GitlabMySqlCommitRepository implements GitlabDataBaseCommitRepositoryInterface
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
     id,
     project_id,
     git_commit_id,
     title,
     created_at,
     web_url,
     
     author_name,
     author_email,
     authored_date,
     
     committer_name,
     committer_email,
     committed_date
     )
VALUES
    (
     :ID,
     :PROJECT_ID,
     :GIT_COMMIT_ID,
     :TITLE,
     :CREATED_AT,
     :WEB_URL,
     
     :AUTHOR_NAME,
     :AUTHOR_EMAIL,
     :AUTHORED_DATE,
     
     :COMMITTER_NAME,
     :COMMITTER_EMAIL,
     :COMMITTED_DATE     
    )
ON DUPLICATE KEY UPDATE id = id
SQL;

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':ID' => $object->id->getValue(),
            ':PROJECT_ID' => $object->projectId->getValue(),
            ':GIT_COMMIT_ID' => $object->gitCommitId->getValue(),
            ':TITLE' => $object->title->getValue(),
            ':CREATED_AT' => $object->createdAt->getValue() ?: null,
            ':WEB_URL' => $object->webUrl->getValue() ?: null,

            ':AUTHOR_NAME' => $object->authorName->getValue(),
            ':AUTHOR_EMAIL' => $object->authorEmail->getValue() ?: null,
            ':AUTHORED_DATE' => $object->authoredDate->getValue(),

            ':COMMITTER_NAME' => $object->committerName->getValue() ?: null,
            ':COMMITTER_EMAIL' => $object->committerEmail->getValue() ?: null,
            ':COMMITTED_DATE' => $object->committedDate->getValue() ?: null,
        ]);
    }
}
