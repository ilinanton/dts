<?php

declare(strict_types=1);

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
            ':TARGET_ID' => $object->targetId->getDbValue(),
            ':TARGET_IID' => $object->targetIid->getDbValue(),
            ':TARGET_TYPE' => $object->targetType->getDbValue(),
            ':AUTHOR_ID' => $object->authorId->value,
            ':TARGET_TITLE' => $object->targetTitle->getDbValue(),
            ':CREATED_AT' => $object->createdAt->getValue(),

            ':PUSH_DATA_ACTION' => $pushData->action->getDbValue(),
            ':PUSH_DATA_COMMIT_TITLE' => $pushData->commitTitle->getDbValue(),
            ':PUSH_DATA_COMMIT_COUNT' => $pushData->commitCount->getDbValue(),
            ':PUSH_DATA_COMMIT_FROM' => $pushData->commitFrom->getDbValue(),
            ':PUSH_DATA_COMMIT_TO' => $pushData->commitTo->getDbValue(),
            ':PUSH_DATA_REF' => $pushData->ref->getDbValue(),
            ':PUSH_DATA_REF_COUNT' => $pushData->refCount->getDbValue(),
            ':PUSH_DATA_REF_TYPE' => $pushData->refType->getDbValue(),

            ':NOTE_BODY' => $note->body->getDbValue(),
        ]);
    }
}
