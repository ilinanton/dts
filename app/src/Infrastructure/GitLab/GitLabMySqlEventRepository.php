<?php

namespace App\Infrastructure\GitLab;

use App\Domain\GitLab\Event\Event;
use App\Domain\GitLab\Event\Repository\GitLabDataBaseEventRepositoryInterface;
use PDO;

final readonly class GitLabMySqlEventRepository implements GitLabDataBaseEventRepositoryInterface
{
    public function __construct(
        private PDO $pdo,
    ) {
    }

    public function save(Event $object): void
    {
        $sql = <<<SQL
INSERT INTO git_lab_event
    (
     id,
     project_id,
     action_name,
     target_id,
     target_iid,
     target_type,
     author_id,
     target_title,
     created_at,
     push_data,
     note
     )
VALUES
    (
     :ID,
     :PROJECT_ID,
     :ACTION_NAME,
     :TARGET_ID,
     :TARGET_IID,
     :TARGET_TYPE,
     :AUTHOR_ID,
     :TARGET_TITLE,
     :CREATED_AT,
     :PUSH_DATA,
     :NOTE
    )
ON DUPLICATE KEY UPDATE id = id
SQL;

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':ID' => $object->getId()->getValue(),
            ':PROJECT_ID' => $object->getProjectId()->getValue(),
            ':ACTION_NAME' => $object->getActionName()->getValue(),
            ':TARGET_ID' => $object->getTargetId()->getValue() ?: null,
            ':TARGET_IID' => $object->getTargetIid()->getValue() ?: null,
            ':TARGET_TYPE' => $object->getTargetType()->getValue() ?: null,
            ':AUTHOR_ID' => $object->getAuthorId()->getValue(),
            ':TARGET_TITLE' => $object->getTargetTitle()->getValue() ?: null,
            ':CREATED_AT' => $object->getCreatedAt()->getValue(),
            ':PUSH_DATA' => $object->getPushData()->getJsonValue() ?: null,
            ':NOTE' => $object->getNote()->getJsonValue() ?: null,
        ]);
    }
}
