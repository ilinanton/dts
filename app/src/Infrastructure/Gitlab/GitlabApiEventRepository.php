<?php

namespace App\Infrastructure\Gitlab;

use App\Domain\Gitlab\Common\Repository\GitlabApiClientInterface;
use App\Domain\Gitlab\Event\EventCollection;
use App\Domain\Gitlab\Event\EventFactory;
use App\Domain\Gitlab\Event\Repository\GitlabApiEventRepositoryInterface;

final readonly class GitlabApiEventRepository implements GitlabApiEventRepositoryInterface
{
    public function __construct(
        private GitlabApiClientInterface $client,
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
