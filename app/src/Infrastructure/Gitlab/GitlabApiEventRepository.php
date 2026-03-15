<?php

declare(strict_types=1);

namespace App\Infrastructure\Gitlab;

use App\Domain\Gitlab\ApiClient\GitlabApiClientEventInterface;
use App\Domain\Gitlab\Event\EventCollection;
use App\Domain\Gitlab\Event\EventFactory;
use App\Domain\Gitlab\Event\Repository\GitlabSourceEventRepositoryInterface;

final readonly class GitlabApiEventRepository implements GitlabSourceEventRepositoryInterface
{
    public function __construct(
        private GitlabApiClientEventInterface $client,
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
        $collection = new EventCollection();

        foreach ($data as $item) {
            $event = $this->eventFactory->create($item);
            $collection->add($event);
        }

        return $collection;
    }
}
