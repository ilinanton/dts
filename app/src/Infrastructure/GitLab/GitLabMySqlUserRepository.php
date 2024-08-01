<?php

namespace App\Infrastructure\GitLab;

use App\Domain\GitLab\User\User;
use App\Domain\GitLab\User\UserCollection;
use App\Domain\GitLab\User\UserFactory;
use App\Domain\GitLab\User\Repository\GitLabDataBaseUserRepositoryInterface;
use PDO;

final class GitLabMySqlUserRepository implements GitLabDataBaseUserRepositoryInterface
{
    private PDO $pdo;
    private UserFactory $memberFactory;

    public function __construct(PDO $pdo, UserFactory $memberFactory)
    {
        $this->pdo = $pdo;
        $this->memberFactory = $memberFactory;
    }

    public function save(User $object): void
    {
        $sql = <<<SQL
INSERT INTO git_lab_user (id, username, name, avatar_url, web_url)
VALUES (:ID, :USERNAME, :NAME, :AVATAR_URL, :WEB_URL)
ON DUPLICATE KEY UPDATE username = :USERNAME, name = :NAME, avatar_url = :AVATAR_URL, web_url = :WEB_URL
SQL;

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':ID' => $object->getId()->getValue(),
            ':USERNAME' => $object->getUsername()->getValue(),
            ':NAME' => $object->getName()->getValue(),
            ':AVATAR_URL' => $object->getAvatarUrl()->getValue(),
            ':WEB_URL' => $object->getWebUrl()->getValue(),
        ]);
    }

    public function getAll(): UserCollection
    {
        $sql = <<<SQL
SELECT id, username, name, avatar_url, web_url
FROM git_lab_user
SQL;

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll();
        $memberCollection = new UserCollection();

        foreach ($data as $item) {
            $project = $this->memberFactory->create($item);
            $memberCollection->add($project);
        }

        return $memberCollection;
    }
}
