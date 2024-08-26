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
     short_id,
     title,
     created_at,
     web_url,
     
     author_name,
     author_email,
     authored_date,
     
     committer_name,
     committer_email,
     committed_date,
     
     stats_additions,
     stats_deletions,
     stats_total
     )
VALUES
    (
     :ID,
     :SHORT_ID,
     :TITLE,
     :CREATED_AT,
     :WEB_URL,
     
     :AUTHOR_NAME,
     :AUTHOR_EMAIL,
     :AUTHORED_DATE,
     
     :COMMITTER_NAME,
     :COMMITTER_EMAIL,
     :COMMITTED_DATE,
     
     :STATS_ADDITIONS,
     :STATS_DELETIONS,
     :STATS_TOTAL
    )
ON DUPLICATE KEY UPDATE id = id
SQL;

        $stmt = $this->pdo->prepare($sql);
        $stats = $object->getStats()->getValue();
        $stmt->execute([
            ':ID' => $object->getId()->getValue(),
            ':SHORT_ID' => $object->getShortId()->getValue(),
            ':TITLE' => $object->getTitle()->getValue(),
            ':CREATED_AT' => $object->getCreatedAt()->getValue() ?: null,
            ':WEB_URL' => $object->getWebUrl()->getValue() ?: null,

            ':AUTHOR_NAME' => $object->getAuthorName()->getValue(),
            ':AUTHOR_EMAIL' => $object->getAuthorEmail()->getValue() ?: null,
            ':AUTHORED_DATE' => $object->getAuthoredDate()->getValue(),

            ':COMMITTER_NAME' => $object->getCommitterName()->getValue() ?: null,
            ':COMMITTER_EMAIL' => $object->getCommitterEmail()->getValue() ?: null,
            ':COMMITTED_DATE' => $object->getCommittedDate()->getValue() ?: null,

            ':STATS_ADDITIONS' => $stats->getAdditions()->getValue(),
            ':STATS_DELETIONS' => $stats->getDeletions()->getValue(),
            ':STATS_TOTAL' => $stats->getTotal()->getValue(),
        ]);
    }
}
