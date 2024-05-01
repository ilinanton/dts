<?php

namespace App\Infrastructure\GitLab;

use App\Domain\GitLab\Project\Repository\GitLabApiProjectRepositoryInterface;

class GitLabApiProjectRepository implements GitLabApiProjectRepositoryInterface
{
    private GitLabApiClientInterface $client;

    public function __construct(GitLabApiClientInterface $client)
    {
        $this->client = $client;
    }

    public function get(): array
    {
        $response = $this->client->get('projects');
        $body = (string)$response->getBody();
        $data = json_decode($body, true);

        return $data;
    }
}
