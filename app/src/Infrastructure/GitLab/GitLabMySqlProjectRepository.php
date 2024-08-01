<?php

namespace App\Infrastructure\GitLab;

use App\Domain\GitLab\Project\Project;
use App\Domain\GitLab\Project\ProjectCollection;
use App\Domain\GitLab\Project\ProjectFactory;
use App\Domain\GitLab\Project\Repository\GitLabDataBaseProjectRepositoryInterface;
use PDO;

final class GitLabMySqlProjectRepository implements GitLabDataBaseProjectRepositoryInterface
{
    private PDO $pdo;
    private ProjectFactory $projectFactory;

    public function __construct(PDO $pdo, ProjectFactory $projectFactory)
    {
        $this->pdo = $pdo;
        $this->projectFactory = $projectFactory;
    }

    public function save(Project $object): void
    {
        $sql = <<<SQL
INSERT INTO git_lab_project (id, name, default_branch, web_url)
VALUES (:ID, :NAME, :DEFAULT_BRANCH, :WEB_URL)
ON DUPLICATE KEY UPDATE name = :NAME, default_branch = :DEFAULT_BRANCH
SQL;

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':ID' => $object->getId()->getValue(),
            ':NAME' => $object->getName()->getValue(),
            ':DEFAULT_BRANCH' => $object->getDefaultBranch()->getValue(),
            ':WEB_URL' => $object->getWebUrl()->getValue(),
        ]);
    }

    public function getAll(): ProjectCollection
    {
        $sql = <<<SQL
SELECT id, name, default_branch, web_url
FROM git_lab_project
SQL;
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll();
        $projectCollection = new ProjectCollection();

        foreach ($data as $item) {
            $project = $this->projectFactory->create($item);
            $projectCollection->add($project);
        }

        return $projectCollection;
    }
}
