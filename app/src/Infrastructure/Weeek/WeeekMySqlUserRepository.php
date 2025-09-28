<?php

declare(strict_types=1);

namespace App\Infrastructure\Weeek;

use App\Domain\Weeek\User\Factory\UserCollectionFromArray;
use App\Domain\Weeek\User\Repository\WeeekDataBaseUserRepositoryInterface;
use App\Domain\Weeek\User\User;
use App\Domain\Weeek\User\UserCollection;
use PDO;

final readonly class WeeekMySqlUserRepository implements WeeekDataBaseUserRepositoryInterface
{
    public function __construct(
        private PDO $pdo,
    ) {
    }

    public function save(User $object): void
    {
        $sql = <<<SQL
INSERT INTO weeek_user (id, email, logo)
VALUES (:ID, :EMAIL, :LOGO)
ON DUPLICATE KEY UPDATE email = :EMAIL, logo = :LOGO
SQL;

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':ID' => $object->id->value,
            ':EMAIL' => $object->email->value,
            ':LOGO' => $object->logo->getDbValue(),
        ]);
    }

    public function getAll(): UserCollection
    {
        $sql = <<<SQL
SELECT id, email, logo
FROM weeek_user
SQL;

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $userFactory = new UserCollectionFromArray($data);
        return $userFactory->create();
    }
}
