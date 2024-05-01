<?php

namespace App\Infrastructure\GitLab;

use App\Domain\GitLab\Member\Repository\GitLabApiMemberRepositoryInterface;

class GitLabApiMemberRepository implements GitLabApiMemberRepositoryInterface
{
    private GitLabApiClientInterface $client;

    public function __construct(GitLabApiClientInterface $client)
    {
        $this->client = $client;
    }

    public function get(): array
    {
        $response = $this->client->get('members');
        $body = (string)$response->getBody();
        $data = json_decode($body, true);

        return $data;
    }
}
