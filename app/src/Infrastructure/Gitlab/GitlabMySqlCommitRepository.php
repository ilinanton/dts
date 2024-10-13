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
            ':ID' => $object->id->value,
            ':PROJECT_ID' => $object->projectId->value,
            ':GIT_COMMIT_ID' => $object->gitCommitId->value,
            ':TITLE' => empty($object->title->value) ? null : $object->title->value,
            ':CREATED_AT' => $object->createdAt->getValue(),
            ':WEB_URL' => $object->webUrl->value,

            ':AUTHOR_NAME' => $object->authorName->value,
            ':AUTHOR_EMAIL' => $object->authorEmail->value,
            ':AUTHORED_DATE' => $object->authoredDate->getValue(),

            ':COMMITTER_NAME' => $object->committerName->value,
            ':COMMITTER_EMAIL' => $object->committerEmail->value,
            ':COMMITTED_DATE' => $object->committedDate->getValue(),
        ]);
    }
}
