<?php

namespace App\Infrastructure\GitLab;

use App\Domain\GitLab\Common\Repository\GitLabApiClientInterface;
use App\Domain\GitLab\User\UserCollection;
use App\Domain\GitLab\User\UserFactory;
use App\Domain\GitLab\User\Repository\GitLabApiUserRepositoryInterface;

final readonly class GitLabApiUserRepository implements GitLabApiUserRepositoryInterface
{
    public function __construct(
        private GitLabApiClientInterface $client,
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
