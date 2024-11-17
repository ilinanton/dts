<?php

declare(strict_types=1);

namespace App\Infrastructure\Gitlab;

use App\Domain\Gitlab\User\Factory\UserCollectionFromArray;
use App\Domain\Gitlab\User\Repository\GitlabDataBaseUserRepositoryInterface;
use App\Domain\Gitlab\User\User;
use App\Domain\Gitlab\User\UserCollection;
use PDO;

final readonly class GitlabMySqlUserRepository implements GitlabDataBaseUserRepositoryInterface
{
    public function __construct(
        private PDO $pdo,
    ) {
    }

    public function save(User $object): void
    {
        $sql = <<<SQL
INSERT INTO gitlab_user (id, username, name, avatar_url, web_url)
VALUES (:ID, :USERNAME, :NAME, :AVATAR_URL, :WEB_URL)
ON DUPLICATE KEY UPDATE username = :USERNAME, name = :NAME, avatar_url = :AVATAR_URL, web_url = :WEB_URL
SQL;

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':ID' => $object->id->value,
            ':USERNAME' => $object->username->value,
            ':NAME' => $object->name->value,
            ':AVATAR_URL' => $object->avatarUrl->value,
            ':WEB_URL' => $object->webUrl->value,
        ]);
    }

    public function getAll(): UserCollection
    {
        $sql = <<<SQL
SELECT id, username, name, avatar_url, web_url
FROM gitlab_user
SQL;

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll();
        $userFactory = new UserCollectionFromArray($data);
        return $userFactory->create();
    }
}
