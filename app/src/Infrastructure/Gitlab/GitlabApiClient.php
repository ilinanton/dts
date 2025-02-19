<?php

declare(strict_types=1);

namespace App\Infrastructure\Gitlab;

use App\Domain\Gitlab\Common\Repository\GitlabApiClientInterface;
use Exception;
use GuzzleHttp\Client;

final readonly class GitlabApiClient implements GitlabApiClientInterface
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
        sleep(1);
        if (count($params) > 0) {
            $uri .= '?' . http_build_query($params);
        }

        $response = $this->client->get($uri);
        $body = (string)$response->getBody();
        if (200 !== $response->getStatusCode()) {
            throw new Exception('Gitlab api error: ' . $body);
        }
        return json_decode($body, true, 512, JSON_THROW_ON_ERROR);
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

    public function getProjectMergeRequests(int $projectId, array $params = []): array
    {
        $uri = 'projects/' . $projectId . '/merge_requests';
        return $this->get($uri, $params);
    }

    public function getProjectEvents(int $projectId, array $params = []): array
    {
        $uri = 'projects/' . $projectId . '/events';
        return $this->get($uri, $params);
    }

    public function getProjectRepositoryCommits(int $projectId, array $params = []): array
    {
        $uri = 'projects/' . $projectId . '/repository/commits';
        return $this->get($uri, $params);
    }

    public function getUserEvents(int $userId, array $params = []): array
    {
        $uri = 'users/' . $userId . '/events';
        return $this->get($uri, $params);
    }

    public function getLabels(array $params = []): array
    {
        $uri = 'groups/' . $this->groupId . '/labels';
        return $this->get($uri, $params);
    }
}
