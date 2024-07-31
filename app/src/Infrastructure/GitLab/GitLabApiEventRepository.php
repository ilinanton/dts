<?php

namespace App\Infrastructure\GitLab;

use App\Domain\GitLab\Common\Repository\GitLabApiClientInterface;
use App\Domain\GitLab\Event\EventCollection;
use App\Domain\GitLab\Event\EventFactory;
use App\Domain\GitLab\Event\Repository\GitLabApiEventRepositoryInterface;

final class GitLabApiEventRepository implements GitLabApiEventRepositoryInterface
{
    private GitLabApiClientInterface $client;
    private EventFactory $eventFactory;

    public function __construct(GitLabApiClientInterface $client, EventFactory $eventFactory)
    {
        $this->client = $client;
        $this->eventFactory = $eventFactory;
    }

    public function getByProjectId(int $projectId, int $page = 1, int $perPage = 20): EventCollection
    {
        $uri = 'projects/' . $projectId . '/events?' . http_build_query([
                'page' => $page,
                'per_page' => $perPage,
            ]);
        $response = $this->client->get($uri);
        $body = (string)$response->getBody();
        $data = json_decode($body, true);

        return $this->createCollection($data);
    }

    public function getByUserId(int $userId, int $page = 1, int $perPage = 20): EventCollection
    {
        $uri = 'users/' . $userId . '/events?' . http_build_query([
                'page' => $page,
                'per_page' => $perPage,
            ]);
        $response = $this->client->get($uri);
        $body = (string)$response->getBody();
        $data = json_decode($body, true);

        return $this->createCollection($data);
    }

    private function createCollection(array $data): EventCollection
    {
        $eventCollection = new EventCollection();
        foreach ($data as $item) {
            $project = $this->eventFactory->create($item);
            $eventCollection->add($project);
        }

        return $eventCollection;
    }
}
