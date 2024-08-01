<?php

namespace App\Infrastructure\GitLab;

use App\Domain\GitLab\Common\Repository\GitLabApiClientInterface;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

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

    public function get(string $uri, array $params = []): ResponseInterface
    {
        if (count($params) > 0) {
            $uri .= '?' . http_build_query($params);
        }
        return $this->client->get($uri);
    }

    public function getGroupMembers(array $params = []): ResponseInterface
    {
        $uri = 'groups/' . $this->groupId . '/members';
        return $this->get($uri, $params);
    }

    public function getGroupProjects(array $params = []): ResponseInterface
    {
        $uri = 'groups/' . $this->groupId . '/projects';
        return $this->get($uri, $params);
    }
}
