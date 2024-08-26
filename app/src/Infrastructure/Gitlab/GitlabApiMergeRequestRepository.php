<?php

namespace App\Infrastructure\Gitlab;

use App\Domain\Gitlab\Common\Repository\GitlabApiClientInterface;
use App\Domain\Gitlab\MergeRequest\MergeRequestCollection;
use App\Domain\Gitlab\MergeRequest\MergeRequestFactory;
use App\Domain\Gitlab\MergeRequest\Repository\GitlabApiMergeRequestRepositoryInterface;

final readonly class GitlabApiMergeRequestRepository implements GitlabApiMergeRequestRepositoryInterface
{
    public function __construct(
        private GitlabApiClientInterface $client,
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
