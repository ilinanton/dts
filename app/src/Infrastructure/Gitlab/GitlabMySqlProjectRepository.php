<?php

namespace App\Infrastructure\Gitlab;

use App\Domain\Gitlab\Project\Project;
use App\Domain\Gitlab\Project\ProjectCollection;
use App\Domain\Gitlab\Project\ProjectFactory;
use App\Domain\Gitlab\Project\Repository\GitlabDataBaseProjectRepositoryInterface;
use PDO;

final readonly class GitlabMySqlProjectRepository implements GitlabDataBaseProjectRepositoryInterface
{
    public function __construct(
        private PDO $pdo,
        private ProjectFactory $projectFactory,
    ) {
    }

    public function save(Project $object): void
    {
        $sql = <<<SQL
INSERT INTO gitlab_project (id, name, default_branch, web_url)
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
FROM gitlab_project
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
