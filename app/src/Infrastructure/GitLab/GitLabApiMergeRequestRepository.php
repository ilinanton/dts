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

    public function get(int $page = 1, int $perPage = 20): MergeRequestCollection
    {
        $uri = 'merge_requests?' . http_build_query([
                'page' => $page,
                'per_page' => $perPage,
            ]);

        $response = $this->client->get($uri);
        $body = (string)$response->getBody();
        $data = json_decode($body, true);
        $mergeRequestCollection = new MergeRequestCollection();

        foreach ($data as $item) {
            $mergeRequest = $this->mergeRequestFactory->create($item);
            $mergeRequestCollection->add($mergeRequest);
        }

        return $mergeRequestCollection;
    }
}