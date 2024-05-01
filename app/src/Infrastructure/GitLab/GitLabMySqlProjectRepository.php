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

    public function save(Project $project): void
    {
        // TODO: Implement save() method.
    }
}
