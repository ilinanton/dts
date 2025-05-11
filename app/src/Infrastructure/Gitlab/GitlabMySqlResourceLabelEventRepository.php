<?php

declare(strict_types=1);

namespace App\Infrastructure\Gitlab;

use App\Domain\Gitlab\ResourceLabelEvent\Repository\GitlabDataBaseResourceLabelEventRepositoryInterface;
use App\Domain\Gitlab\ResourceLabelEvent\ResourceLabelEvent;
use PDO;

final readonly class GitlabMySqlResourceLabelEventRepository implements GitlabDataBaseResourceLabelEventRepositoryInterface
{
    public function __construct(
        private PDO $pdo,
    ) {
    }

    public function save(ResourceLabelEvent $object): void
    {
        $sql = <<<SQL
INSERT INTO gitlab_resource_label_event
    (
     id,
     user_id,
     created_at,
     resource_type,
     resource_id,
     label_id,
     action_name,
     project_id
    )
VALUES
    (
     :ID,
     :USER_ID,
     :CREATED_AT,
     :RESOURCE_TYPE,
     :RESOURCE_ID,
     :LABEL_ID,
     :ACTION_NAME,
     :PROJECT_ID
    )
ON DUPLICATE KEY UPDATE id = id
SQL;

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':ID' => $object->id->value,
            ':USER_ID' => $object->userId->value,
            ':CREATED_AT' => $object->createdAt->getValue(),
            ':RESOURCE_TYPE' => $object->resourceType->value,
            ':RESOURCE_ID' => $object->resourceId->value,
            ':LABEL_ID' => $object->labelId->value,
            ':ACTION_NAME' => $object->actionName->value,
            ':PROJECT_ID' => $object->projectId->value,
        ]);
    }
}
