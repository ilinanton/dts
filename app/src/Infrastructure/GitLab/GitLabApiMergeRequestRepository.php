<?php

namespace App\Infrastructure\GitLab;

use App\Domain\GitLab\Common\Repository\GitLabApiClientInterface;
use App\Domain\GitLab\MergeRequest\MergeRequestCollection;
use App\Domain\GitLab\MergeRequest\MergeRequestFactory;
use App\Domain\GitLab\MergeRequest\Repository\GitLabApiMergeRequestRepositoryInterface;

final readonly class GitLabApiMergeRequestRepository implements GitLabApiMergeRequestRepositoryInterface
{
    public function __construct(
        private GitLabApiClientInterface $client,
        private MergeRequestFactory $mergeRequestFactory,
    ) {
    }

    public function get(int $projectId, array $params = []): MergeRequestCollection
    {
        $data = $this->client->getProjectMergeRequests($projectId, $params);
        $mergeRequestCollection = new MergeRequestCollection();

        foreach ($data as $item) {
            $mergeRequest = $this->mergeRequestFactory->create($item);
            $mergeRequestCollection->add($mergeRequest);
        }

        return $mergeRequestCollection;
    }
}
