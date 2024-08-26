<?php

namespace App\Infrastructure\Gitlab;

use App\Domain\Gitlab\Common\Repository\GitlabApiClientInterface;
use App\Domain\Gitlab\Project\ProjectCollection;
use App\Domain\Gitlab\Project\ProjectFactory;
use App\Domain\Gitlab\Project\Repository\GitlabApiProjectRepositoryInterface;

final readonly class GitlabApiProjectRepository implements GitlabApiProjectRepositoryInterface
{
    public function __construct(
        private GitlabApiClientInterface $client,
        private ProjectFactory $projectFactory,
    ) {
    }

    public function get(array $params = []): ProjectCollection
    {
        $data = $this->client->getGroupProjects($params);
        $projectCollection = new ProjectCollection();

        foreach ($data as $item) {
            $project = $this->projectFactory->create($item);
            $projectCollection->add($project);
        }

        return $projectCollection;
    }
}
