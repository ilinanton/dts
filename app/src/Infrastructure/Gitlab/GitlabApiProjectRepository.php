<?php

declare(strict_types=1);

namespace App\Infrastructure\Gitlab;

use App\Domain\Gitlab\Common\Repository\GitlabApiClientInterface;
use App\Domain\Gitlab\Project\Factory\ProjectCollectionFromArray;
use App\Domain\Gitlab\Project\ProjectCollection;
use App\Domain\Gitlab\Project\Repository\GitlabApiProjectRepositoryInterface;

final readonly class GitlabApiProjectRepository implements GitlabApiProjectRepositoryInterface
{
    public function __construct(
        private GitlabApiClientInterface $client,
    ) {
    }

    public function get(array $params = []): ProjectCollection
    {
        $data = $this->client->getGroupProjects($params);
        $projectCollectionFactory = new ProjectCollectionFromArray($data);
        return $projectCollectionFactory->create();
    }
}
