<?php

namespace App\Infrastructure\GitLab;

use App\Domain\GitLab\Project\ProjectCollection;
use App\Domain\GitLab\Project\ProjectFactory;
use App\Domain\GitLab\Project\Repository\GitLabApiProjectRepositoryInterface;

final class GitLabApiProjectRepository implements GitLabApiProjectRepositoryInterface
{
    private GitLabApiClientInterface $client;
    private ProjectFactory $projectFactory;

    public function __construct(GitLabApiClientInterface $client, ProjectFactory $projectFactory)
    {
        $this->client = $client;
        $this->projectFactory = $projectFactory;
    }

    public function get(): ProjectCollection
    {
        $response = $this->client->get('projects');
        $body = (string)$response->getBody();
        $data = json_decode($body, true);

        $projectCollection = new ProjectCollection();

        foreach ($data as $item) {
            $project = $this->projectFactory->create($item);
            $projectCollection->add($project);
        }

        return $projectCollection;
    }
}
