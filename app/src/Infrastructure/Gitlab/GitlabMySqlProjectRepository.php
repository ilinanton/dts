<?php

declare(strict_types=1);

namespace App\Infrastructure\Gitlab;

use App\Domain\Gitlab\Project\Factory\ProjectCollectionFromArray;
use App\Domain\Gitlab\Project\Project;
use App\Domain\Gitlab\Project\ProjectCollection;
use App\Domain\Gitlab\Project\Repository\GitlabDataBaseProjectRepositoryInterface;
use PDO;

final readonly class GitlabMySqlProjectRepository implements GitlabDataBaseProjectRepositoryInterface
{
    public function __construct(
        private PDO $pdo,
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
            ':ID' => $object->id->value,
            ':NAME' => $object->name->value,
            ':DEFAULT_BRANCH' => $object->defaultBranch->value,
            ':SSH_URL_TO_REPO' => $object->sshUrlToRepo->value,
            ':HTTP_URL_TO_REPO' => $object->httpUrlToRepo->value,
            ':WEB_URL' => $object->webUrl->value,
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
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $projectCollectionFactory = new ProjectCollectionFromArray($data);
        return $projectCollectionFactory->create();
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

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $projectCollectionFactory = new ProjectCollectionFromArray($data);
        return $projectCollectionFactory->create();
    }
}
