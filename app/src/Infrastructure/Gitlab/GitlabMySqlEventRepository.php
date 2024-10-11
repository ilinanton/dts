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
        $pushData = $object->pushData->getValue();
        $note = $object->note->getValue();
        $stmt->execute([
            ':ID' => $object->id->getValue(),
            ':PROJECT_ID' => $object->projectId->getValue(),
            ':ACTION_NAME' => $object->actionName->getValue(),
            ':TARGET_ID' => $object->targetId->getValue() ?: null,
            ':TARGET_IID' => $object->targetIid->getValue() ?: null,
            ':TARGET_TYPE' => $object->targetType->getValue() ?: null,
            ':AUTHOR_ID' => $object->authorId->getValue(),
            ':TARGET_TITLE' => $object->targetTitle->getValue() ?: null,
            ':CREATED_AT' => $object->createdAt->getValue(),

            ':PUSH_DATA_ACTION' => $pushData->action->value ?: null,
            ':PUSH_DATA_COMMIT_TITLE' => $pushData->commitTitle->value ?: null,
            ':PUSH_DATA_COMMIT_COUNT' => $pushData->commitCount->getValue() ?: null,
            ':PUSH_DATA_COMMIT_FROM' => $pushData->commitFrom->value ?: null,
            ':PUSH_DATA_COMMIT_TO' => $pushData->commitTo->value ?: null,
            ':PUSH_DATA_REF' => $pushData->ref->value ?: null,
            ':PUSH_DATA_REF_COUNT' => $pushData->refCount->getValue() ?: null,
            ':PUSH_DATA_REF_TYPE' => $pushData->refType->value ?: null,

            ':NOTE_BODY' => $note->body->value ?: null,
        ]);
    }
}
