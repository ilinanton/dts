<?php

declare(strict_types=1);

namespace App\Infrastructure\Gitlab;

use App\Domain\Gitlab\Source\GitlabSourceProjectInterface;
use App\Domain\Gitlab\Project\Factory\ProjectCollectionFromArray;
use App\Domain\Gitlab\Project\ProjectCollection;
use App\Domain\Gitlab\Project\Repository\GitlabSourceProjectRepositoryInterface;

final readonly class GitlabApiProjectRepository implements GitlabSourceProjectRepositoryInterface
{
    public function __construct(
        private GitlabSourceProjectInterface $client,
        private array $excludedProjectIds,
    ) {
    }

    public function get(array $params = []): ProjectCollection
    {
        $data = $this->client->getGroupProjects($params);
        $data = array_filter($data, function (array $value): bool {
            return !in_array($value['id'], $this->excludedProjectIds);
        });
        $projectCollectionFactory = new ProjectCollectionFromArray();
        return $projectCollectionFactory->create($data);
    }
}
