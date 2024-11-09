<?php

declare(strict_types=1);

namespace App\Infrastructure\Gitlab;

use App\Domain\Gitlab\MergeRequest\MergeRequest;
use App\Domain\Gitlab\MergeRequest\Repository\GitlabDataBaseMergeRequestRepositoryInterface;
use PDO;

final readonly class GitlabMySqlMergeRequestRepository implements GitlabDataBaseMergeRequestRepositoryInterface
{
    public function __construct(
        private PDO $pdo,
    ) {
    }

    public function save(MergeRequest $object): void
    {
        $sql = <<<SQL
INSERT INTO gitlab_merge_request
    (
     id,
     iid,
     project_id,
     title, 
     state,
     merged_at,
     created_at, 
     updated_at,
     target_branch,
     source_branch,
     author_id,
     web_url
    )
VALUES
    (
     :ID,
     :IID,
     :PROJECT_ID,
     :TITLE, 
     :STATE,
     :MERGED_AT,
     :CREATED_AT, 
     :UPDATED_AT,
     :TARGET_BRANCH,
     :SOURCE_BRANCH,
     :AUTHOR_ID,
     :WEB_URL
    )
ON DUPLICATE KEY UPDATE
     title = :TITLE, 
     state = :STATE,
     merged_at = :MERGED_AT,
     updated_at = :UPDATED_AT,
     target_branch = :TARGET_BRANCH,
     web_url = :WEB_URL
SQL;

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':ID' => $object->id->value,
            ':IID' => $object->iid->value,
            ':PROJECT_ID' => $object->projectId->value,
            ':TITLE' => $object->title->value,
            ':STATE' => $object->state->value,
            ':MERGED_AT' => $object->mergedAt->getDbValue(),
            ':CREATED_AT' => $object->createdAt->getValue(),
            ':UPDATED_AT' => $object->updatedAt->getDbValue(),
            ':TARGET_BRANCH' => $object->targetBranch->value,
            ':SOURCE_BRANCH' => $object->sourceBranch->value,
            ':AUTHOR_ID' => $object->authorId->value,
            ':WEB_URL' => $object->webUrl->value,
        ]);
    }
}
