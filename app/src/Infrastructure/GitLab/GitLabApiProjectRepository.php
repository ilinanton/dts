<?php

namespace App\Infrastructure\GitLab;

use App\Domain\GitLab\Common\GitLabApiClientInterface;
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

    public function get(int $page = 1, int $perPage = 20): ProjectCollection
    {
        $uri = 'projects?' . http_build_query([
                'page' => $page,
                'per_page' => $perPage,
            ]);
        $response = $this->client->get($uri);
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
