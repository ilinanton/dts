<?php

namespace App\Infrastructure\GitLab;

use App\Domain\GitLab\Common\Repository\GitLabApiClientInterface;
use GuzzleHttp\Client;

final class GitLabApiClient implements GitLabApiClientInterface
{
    private Client $client;
    private int $groupId;

    public function __construct(string $baseUri, string $privateToken, int $groupId)
    {
        $this->client = new Client([
            'base_uri' => $baseUri,
            'verify' => false,
            'http_errors' => false,
            'headers' => [
                'Content-Type' => 'application/json',
                'PRIVATE-TOKEN' => $privateToken,
            ],
            'connect_timeout' => 10,
            'timeout' => 30,
        ]);

        $this->groupId = $groupId;
    }

    public function get(string $uri, array $params = []): array
    {
        if (count($params) > 0) {
            $uri .= '?' . http_build_query($params);
        }

        $response = $this->client->get($uri);
        $body = (string)$response->getBody();

        return json_decode($body, 512, JSON_THROW_ON_ERROR);
    }

    public function getGroupMembers(array $params = []): array
    {
        $uri = 'groups/' . $this->groupId . '/members';
        return $this->get($uri, $params);
    }

    public function getGroupProjects(array $params = []): array
    {
        $uri = 'groups/' . $this->groupId . '/projects';
        return $this->get($uri, $params);
    }

    public function getGroupMergeRequests(array $params = []): array
    {
        $uri = 'groups/' . $this->groupId . '/merge_requests';
        return $this->get($uri, $params);
    }

    public function getProjectEvents(int $projectId, array $params = []): array
    {
        $uri = 'projects/' . $projectId . '/events';
        return $this->get($uri, $params);
    }

    public function getUserEvents(int $userId, array $params = []): array
    {
        $uri = 'users/' . $userId . '/events';
        return $this->get($uri, $params);
    }
}
