<?php

namespace App\Infrastructure\Gitlab;

use App\Domain\Gitlab\Common\Repository\GitlabApiClientInterface;
use App\Domain\Gitlab\User\Repository\GitlabApiUserRepositoryInterface;
use App\Domain\Gitlab\User\UserCollection;
use App\Domain\Gitlab\User\UserFactory;

final readonly class GitlabApiUserRepository implements GitlabApiUserRepositoryInterface
{
    public function __construct(
        private GitlabApiClientInterface $client,
        private UserFactory $memberFactory,
    ) {
    }

    public function get(array $params = []): UserCollection
    {
        $data = $this->client->getGroupMembers();
        $userCollection = new UserCollection();

        foreach ($data as $item) {
            $user = $this->memberFactory->create($item);
            $userCollection->add($user);
        }

        return $userCollection;
    }
}
