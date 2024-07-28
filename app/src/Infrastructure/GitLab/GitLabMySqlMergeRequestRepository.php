<?php

namespace App\Infrastructure\GitLab;

use App\Domain\GitLab\MergeRequest\MergeRequest;
use App\Domain\GitLab\MergeRequest\Repository\GitLabDataBaseMergeRequestRepositoryInterface;
use PDO;

final class GitLabMySqlMergeRequestRepository implements GitLabDataBaseMergeRequestRepositoryInterface
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function save(MergeRequest $object): void
    {
        $sql = <<<SQL
INSERT INTO git_lab_merge_request
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
            ':ID' => $object->getId()->getValue(),
            ':IID' => $object->getIid()->getValue(),
            ':PROJECT_ID' => $object->getProjectId()->getValue(),
            ':TITLE' => $object->getTitle()->getValue(),
            ':STATE' => $object->getState()->getValue(),
            ':MERGED_AT' => $object->getMergedAt()->getValue(),
            ':CREATED_AT' => $object->getCreatedAt()->getValue(),
            ':UPDATED_AT' => $object->getUpdatedAt()->getValue(),
            ':TARGET_BRANCH' => $object->getTargetBranch()->getValue(),
            ':SOURCE_BRANCH' => $object->getSourceBranch()->getValue(),
            ':AUTHOR_ID' => $object->getAuthorId()->getValue(),
            ':WEB_URL' => $object->getWebUrl()->getValue(),
        ]);
    }
}
