<?php

namespace App\Infrastructure\GitLab;

use App\Domain\GitLab\Project\Project;
use App\Domain\GitLab\Project\Repository\GitLabDataBaseProjectRepositoryInterface;
use PDO;

final class GitLabMySqlProjectRepository implements GitLabDataBaseProjectRepositoryInterface
{
    private PDO $pdo;

    public function __construct(string $dsn, string $userName, string $password)
    {
        $this->pdo = new PDO($dsn, $userName, $password);
    }

    public function save(Project $object): void
    {
        $sql = <<<SQL
INSERT INTO git_lab_project (id, name, default_branch, web_url)
VALUES (:ID, :NAME, :DEFAULT_BRANCH, :WEB_URL)
ON DUPLICATE KEY UPDATE name = :NAME, default_branch = :DEFAULT_BRANCH, web_url = :WEB_URL
SQL;

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':ID' => $object->getId()->getValue(),
            ':NAME' => $object->getName()->getValue(),
            ':DEFAULT_BRANCH' => $object->getDefaultBranch()->getValue(),
            ':WEB_URL' => $object->getWebUrl()->getValue(),
        ]);
    }
}
