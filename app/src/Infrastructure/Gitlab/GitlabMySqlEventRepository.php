<?php

declare(strict_types=1);

namespace App\Infrastructure\Gitlab;

use App\Domain\Gitlab\Event\Event;
use App\Domain\Gitlab\Event\Repository\GitlabStorageEventRepositoryInterface;
use PDO;

final readonly class GitlabMySqlEventRepository implements GitlabStorageEventRepositoryInterface
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
            ':TARGET_ID' => DbValueConverter::nullableInt($object->targetId),
            ':TARGET_IID' => DbValueConverter::nullableInt($object->targetIid),
            ':TARGET_TYPE' => DbValueConverter::nullableString($object->targetType),
            ':AUTHOR_ID' => $object->authorId->value,
            ':TARGET_TITLE' => DbValueConverter::nullableString($object->targetTitle),
            ':CREATED_AT' => $object->createdAt->getValue(),

            ':PUSH_DATA_ACTION' => DbValueConverter::nullableString($pushData->action),
            ':PUSH_DATA_COMMIT_TITLE' => DbValueConverter::nullableString($pushData->commitTitle),
            ':PUSH_DATA_COMMIT_COUNT' => DbValueConverter::nullableInt($pushData->commitCount),
            ':PUSH_DATA_COMMIT_FROM' => DbValueConverter::nullableString($pushData->commitFrom),
            ':PUSH_DATA_COMMIT_TO' => DbValueConverter::nullableString($pushData->commitTo),
            ':PUSH_DATA_REF' => DbValueConverter::nullableString($pushData->ref),
            ':PUSH_DATA_REF_COUNT' => DbValueConverter::nullableInt($pushData->refCount),
            ':PUSH_DATA_REF_TYPE' => DbValueConverter::nullableString($pushData->refType),

            ':NOTE_BODY' => DbValueConverter::nullableString($note->body),
        ]);
    }
}
