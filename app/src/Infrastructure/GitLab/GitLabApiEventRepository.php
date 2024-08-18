<?php

namespace App\Infrastructure\GitLab;

use App\Domain\GitLab\Common\Repository\GitLabApiClientInterface;
use App\Domain\GitLab\Event\EventCollection;
use App\Domain\GitLab\Event\EventFactory;
use App\Domain\GitLab\Event\Repository\GitLabApiEventRepositoryInterface;

final readonly class GitLabApiEventRepository implements GitLabApiEventRepositoryInterface
{
    public function __construct(
        private GitLabApiClientInterface $client,
        private EventFactory $eventFactory,
    ) {
    }

    public function getByProjectId(int $projectId, array $params = []): EventCollection
    {
        $data = $this->client->getProjectEvents($projectId, $params);
        return $this->createCollection($data);
    }

    public function getByUserId(int $userId, array $params = []): EventCollection
    {
        $data = $this->client->getUserEvents($userId, $params);
        return $this->createCollection($data);
    }

    private function createCollection(array $data): EventCollection
    {
        $eventCollection = new EventCollection();
        foreach ($data as $item) {
            $event = $this->eventFactory->create($item);
            $eventCollection->add($event);
        }

        return $eventCollection;
    }
}
