<?php

declare(strict_types=1);

namespace App\Infrastructure\Gitlab;

use App\Domain\Gitlab\Source\GitlabSourceUserInterface;
use App\Infrastructure\Gitlab\Factory\UserCollectionFromArray;
use App\Domain\Gitlab\User\Repository\GitlabSourceUserRepositoryInterface;
use App\Domain\Gitlab\User\UserCollection;

final readonly class GitlabApiUserRepository implements GitlabSourceUserRepositoryInterface
{
    public function __construct(
        private GitlabSourceUserInterface $client,
        private array $excludedUserIds,
    ) {
    }

    public function get(array $params = []): UserCollection
    {
        $data = $this->client->getGroupMembers($params);
        $data = array_filter($data, function (array $value): bool {
            return !in_array($value['id'], $this->excludedUserIds);
        });
        $userCollectionFactory = new UserCollectionFromArray();
        return $userCollectionFactory->create($data);
    }
}
