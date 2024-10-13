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
        $pushData = $object->pushData->value;
        $note = $object->note->value;
        $stmt->execute([
            ':ID' => $object->id->value,
            ':PROJECT_ID' => $object->projectId->value,
            ':ACTION_NAME' => $object->actionName->value,
            ':TARGET_ID' => empty($object->targetId->value) ? null : $object->targetId->value,
            ':TARGET_IID' => empty($object->targetIid->value) ? null : $object->targetIid->value,
            ':TARGET_TYPE' => empty($object->targetType->value) ? null : $object->targetType->value,
            ':AUTHOR_ID' => $object->authorId->value,
            ':TARGET_TITLE' => empty($object->targetTitle->value) ? null : $object->targetTitle->value,
            ':CREATED_AT' => $object->createdAt->getValue(),

            ':PUSH_DATA_ACTION' => empty($pushData->action->value) ? null : $pushData->action->value,
            ':PUSH_DATA_COMMIT_TITLE' => empty($pushData->commitTitle->value) ? null : $pushData->commitTitle->value,
            ':PUSH_DATA_COMMIT_COUNT' => empty($pushData->commitCount->value) ? null : $pushData->commitCount->value,
            ':PUSH_DATA_COMMIT_FROM' => empty($pushData->commitFrom->value) ? null : $pushData->commitFrom->value,
            ':PUSH_DATA_COMMIT_TO' => empty($pushData->commitTo->value) ? null : $pushData->commitTo->value,
            ':PUSH_DATA_REF' => empty($pushData->ref->value) ? null : $pushData->ref->value,
            ':PUSH_DATA_REF_COUNT' => empty($pushData->refCount->value) ? null : $pushData->refCount->value,
            ':PUSH_DATA_REF_TYPE' => empty($pushData->refType->value) ? null : $pushData->refType->value,

            ':NOTE_BODY' => empty($note->body->value) ? null : $note->body->value,
        ]);
    }
}
