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

    public function get(int $page = 1, int $perPage = 20): MemberCollection
    {
        $uri = 'members?' . http_build_query([
                'page' => $page,
                'per_page' => $perPage,
            ]);
        $response = $this->client->get($uri);
        $body = (string)$response->getBody();
        $data = json_decode($body, true);
        $memberCollection = new MemberCollection();

        foreach ($data as $item) {
            $project = $this->memberFactory->create($item);
            $memberCollection->add($project);
        }

        return $memberCollection;
    }
}
