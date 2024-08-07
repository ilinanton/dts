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
        $memberCollection = new UserCollection();

        foreach ($data as $item) {
            $project = $this->memberFactory->create($item);
            $memberCollection->add($project);
        }

        return $memberCollection;
    }
}
