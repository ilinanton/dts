<?php

declare(strict_types=1);

namespace App\Infrastructure\Gitlab;

use App\Domain\Gitlab\Label\Label;
use App\Domain\Gitlab\Label\Repository\GitlabDataBaseLabelRepositoryInterface;
use PDO;

final readonly class GitlabMySqlLabelRepository implements GitlabDataBaseLabelRepositoryInterface
{
    public function __construct(
        private PDO $pdo,
    ) {
    }

    public function save(Label $object): void
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
