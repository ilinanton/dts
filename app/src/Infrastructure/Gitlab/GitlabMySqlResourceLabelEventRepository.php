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
INSERT INTO gitlab_label (id, name, color)
VALUES (:ID, :NAME, :COLOR)
ON DUPLICATE KEY UPDATE name = :NAME, color = :COLOR
SQL;

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':ID' => $object->id->value,
            ':NAME' => $object->name->value,
            ':COLOR' => $object->color->value,
        ]);
    }
}
