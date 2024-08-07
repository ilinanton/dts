<?php

namespace App\Infrastructure\GitLab;

use App\Domain\GitLab\Common\Repository\GitLabApiClientInterface;
use App\Domain\GitLab\Project\ProjectCollection;
use App\Domain\GitLab\Project\ProjectFactory;
use App\Domain\GitLab\Project\Repository\GitLabApiProjectRepositoryInterface;

final readonly class GitLabApiProjectRepository implements GitLabApiProjectRepositoryInterface
{
    public function __construct(
        private GitLabApiClientInterface $client,
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
