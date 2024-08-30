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
INSERT INTO gitlab_project (id, name, default_branch, ssh_url_to_repo, http_url_to_repo, web_url)
VALUES (:ID, :NAME, :DEFAULT_BRANCH, :SSH_URL_TO_REPO, :HTTP_URL_TO_REPO, :WEB_URL)
ON DUPLICATE KEY UPDATE
    name = :NAME,
    default_branch = :DEFAULT_BRANCH,
    ssh_url_to_repo = :SSH_URL_TO_REPO,
    http_url_to_repo = :HTTP_URL_TO_REPO,
    web_url = :WEB_URL
SQL;

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':ID' => $object->id->getValue(),
            ':NAME' => $object->name->getValue(),
            ':DEFAULT_BRANCH' => $object->defaultBranch->getValue(),
            ':SSH_URL_TO_REPO' => $object->sshUrlToRepo->getValue(),
            ':HTTP_URL_TO_REPO' => $object->httpUrlToRepo->getValue(),
            ':WEB_URL' => $object->webUrl->getValue(),
        ]);
    }

    public function getAll(): ProjectCollection
    {
        $sql = <<<SQL
SELECT id, name, default_branch, ssh_url_to_repo, http_url_to_repo, web_url
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

    public function findByUrlToRepo(string $url): ProjectCollection
    {
        $sql = <<<SQL
SELECT id, name, default_branch, ssh_url_to_repo, http_url_to_repo, web_url
FROM gitlab_project
WHERE ssh_url_to_repo = :URL or http_url_to_repo = :URL
SQL;
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':URL' => $url,
        ]);

        $data = $stmt->fetchAll();
        $projectCollection = new ProjectCollection();

        foreach ($data as $item) {
            $project = $this->projectFactory->create($item);
            $projectCollection->add($project);
        }

        return $projectCollection;
    }
}
