<?php

declare(strict_types=1);

namespace App\Infrastructure\Gitlab;

use App\Domain\Gitlab\Common\Repository\GitlabApiClientInterface;
use App\Domain\Gitlab\User\Factory\UserCollectionFromArray;
use App\Domain\Gitlab\User\Repository\GitlabApiUserRepositoryInterface;
use App\Domain\Gitlab\User\UserCollection;

final readonly class GitlabApiUserRepository implements GitlabApiUserRepositoryInterface
{
    public function __construct(
        private GitlabApiClientInterface $client,
    ) {
    }

    public function get(array $params = []): UserCollection
    {
        $data = $this->client->getGroupMembers();
        $userCollectionFactory = new UserCollectionFromArray($data);
        return $userCollectionFactory->create();
    }
}
