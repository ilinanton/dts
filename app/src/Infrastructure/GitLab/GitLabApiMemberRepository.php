<?php

namespace App\Infrastructure\GitLab;

use App\Domain\GitLab\Common\Repository\GitLabApiClientInterface;
use App\Domain\GitLab\Member\MemberCollection;
use App\Domain\GitLab\Member\MemberFactory;
use App\Domain\GitLab\Member\Repository\GitLabApiMemberRepositoryInterface;

final class GitLabApiMemberRepository implements GitLabApiMemberRepositoryInterface
{
    private GitLabApiClientInterface $client;
    private MemberFactory $memberFactory;

    public function __construct(GitLabApiClientInterface $client, MemberFactory $memberFactory)
    {
        $this->client = $client;
        $this->memberFactory = $memberFactory;
    }

    public function get(array $params = []): MemberCollection
    {
        $data = $this->client->getGroupMembers();
        $memberCollection = new MemberCollection();

        foreach ($data as $item) {
            $project = $this->memberFactory->create($item);
            $memberCollection->add($project);
        }

        return $memberCollection;
    }
}
