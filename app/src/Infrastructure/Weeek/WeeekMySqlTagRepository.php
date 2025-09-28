<?php

declare(strict_types=1);

namespace App\Infrastructure\Weeek;

use App\Domain\Weeek\Tag\Factory\TagCollectionFromArray;
use App\Domain\Weeek\Tag\Repository\WeeekDataBaseTagRepositoryInterface;
use App\Domain\Weeek\Tag\Tag;
use App\Domain\Weeek\Tag\TagCollection;
use PDO;

final readonly class WeeekMySqlTagRepository implements WeeekDataBaseTagRepositoryInterface
{
    public function __construct(
        private PDO $pdo,
    ) {
    }

    public function save(Tag $object): void
    {
        $sql = <<<SQL
INSERT INTO weeek_tag (id, title, color)
VALUES (:ID, :TITLE, :COLOR)
ON DUPLICATE KEY UPDATE title = :TITLE, color = :COLOR
SQL;

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':ID' => $object->id->value,
            ':TITLE' => $object->title->value,
            ':COLOR' => $object->color->value,
        ]);
    }

    public function getAll(): TagCollection
    {
        $sql = <<<SQL
SELECT id, title, color
FROM weeek_tag
SQL;

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $factory = new TagCollectionFromArray($data);
        return $factory->create();
    }
}
