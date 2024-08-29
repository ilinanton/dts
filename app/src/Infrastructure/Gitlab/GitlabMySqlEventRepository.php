<?php

namespace App\Infrastructure\Gitlab;

use App\Domain\Gitlab\Event\Event;
use App\Domain\Gitlab\Event\Repository\GitlabDataBaseEventRepositoryInterface;
use PDO;

final readonly class GitlabMySqlEventRepository implements GitlabDataBaseEventRepositoryInterface
{
    public function __construct(
        private PDO $pdo,
    ) {
    }

    public function save(Event $object): void
    {
        $sql = <<<SQL
INSERT INTO gitlab_event
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
     
     push_data_action,
     push_data_commit_title,
     push_data_commit_count,
     push_data_commit_from,
     push_data_commit_to,
     push_data_ref,
     push_data_ref_count,
     push_data_ref_type,
     
     note_body
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
     
     :PUSH_DATA_ACTION,
     :PUSH_DATA_COMMIT_TITLE,
     :PUSH_DATA_COMMIT_COUNT,
     :PUSH_DATA_COMMIT_FROM,
     :PUSH_DATA_COMMIT_TO,
     :PUSH_DATA_REF,
     :PUSH_DATA_REF_COUNT,
     :PUSH_DATA_REF_TYPE,
     
     :NOTE_BODY
    )
ON DUPLICATE KEY UPDATE id = id
SQL;

        $stmt = $this->pdo->prepare($sql);
        $pushData = $object->getPushData()->getValue();
        $note = $object->getNote()->getValue();
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

            ':PUSH_DATA_ACTION' => $pushData->getAction()->getValue() ?: null,
            ':PUSH_DATA_COMMIT_TITLE' => $pushData->getCommitTitle()->getValue() ?: null,
            ':PUSH_DATA_COMMIT_COUNT' => $pushData->getCommitCount()->getValue() ?: null,
            ':PUSH_DATA_COMMIT_FROM' => $pushData->getCommitFrom()->getValue() ?: null,
            ':PUSH_DATA_COMMIT_TO' => $pushData->getCommitTo()->getValue() ?: null,
            ':PUSH_DATA_REF' => $pushData->getRef()->getValue() ?: null,
            ':PUSH_DATA_REF_COUNT' => $pushData->getRefCount()->getValue() ?: null,
            ':PUSH_DATA_REF_TYPE' => $pushData->getRefType()->getValue() ?: null,

            ':NOTE_BODY' => $note->getBody()->getValue() ?: null,
        ]);
    }
}
