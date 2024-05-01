<?php

namespace App\Infrastructure\GitLab;

use App\Domain\GitLab\Common\Repository\GitLabApiRepositoryInterface;

class GitLabApiRepository implements GitLabApiRepositoryInterface
{
    private GitLabApiClientInterface $client;

    public function __construct(GitLabApiClientInterface $client)
    {
        $this->client = $client;
    }

    public function getGroupMembers(): array
    {
        $response = $this->client->get('members');
        $body = (string)$response->getBody();
        $data = json_decode($body, true);

        return $data;
    }

    public function getGroupProjects(): array
    {
        $response = $this->client->get('projects');
        $body = (string)$response->getBody();
        $data = json_decode($body, true);

        return $data;
    }
}
