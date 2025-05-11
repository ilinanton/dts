<?php

declare(strict_types=1);

namespace App\Infrastructure\Gitlab;

use App\Domain\Gitlab\Common\Repository\GitlabApiClientInterface;
use App\Domain\Gitlab\MergeRequest\MergeRequestCollection;
use App\Domain\Gitlab\MergeRequest\MergeRequestFactory;
use App\Domain\Gitlab\MergeRequest\Repository\GitlabApiMergeRequestRepositoryInterface;

final readonly class GitlabApiMergeRequestRepository implements GitlabApiMergeRequestRepositoryInterface
{
    public function __construct(
        private GitlabApiClientInterface $client,
    ) {
    }

    public function get(int $projectId, array $params = []): MergeRequestCollection
    {
        $data = $this->client->getProjectMergeRequests($projectId, $params);
        $collection = new MergeRequestCollection();
        $factory = new MergeRequestFactory();

        foreach ($data as $item) {
            $item['author_id'] = $item['author']['id'];
            $mergeRequest = $factory->create($item);
            $collection->add($mergeRequest);
        }

        return $collection;
    }
}
