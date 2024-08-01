<?php

namespace App\Infrastructure\GitLab;

use App\Domain\GitLab\Common\Repository\GitLabApiClientInterface;
use App\Domain\GitLab\MergeRequest\MergeRequestCollection;
use App\Domain\GitLab\MergeRequest\MergeRequestFactory;
use App\Domain\GitLab\MergeRequest\Repository\GitLabApiMergeRequestRepositoryInterface;

final class GitLabApiMergeRequestRepository implements GitLabApiMergeRequestRepositoryInterface
{
    private GitLabApiClientInterface $client;
    private MergeRequestFactory $mergeRequestFactory;

    public function __construct(GitLabApiClientInterface $client, MergeRequestFactory $mergeRequestFactory)
    {
        $this->client = $client;
        $this->mergeRequestFactory = $mergeRequestFactory;
    }

    public function get(array $params = []): MergeRequestCollection
    {
        $data = $this->client->getGroupMergeRequests($params);
        $mergeRequestCollection = new MergeRequestCollection();

        foreach ($data as $item) {
            $mergeRequest = $this->mergeRequestFactory->create($item);
            $mergeRequestCollection->add($mergeRequest);
        }

        return $mergeRequestCollection;
    }
}
