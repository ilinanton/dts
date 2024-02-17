<?php

namespace App\Infrastructure\GitLab;

use App\Domain\GitLab\Common\Repository\ApiInterface;

class Api implements ApiInterface
{
    private ApiClientInterface $client;

    public function __construct(ApiClientInterface $client)
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
