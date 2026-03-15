<?php

declare(strict_types=1);

namespace App\Infrastructure\Gitlab;

use App\Domain\Gitlab\ApiClient\GitlabApiClientCommitInterface;
use App\Domain\Gitlab\Commit\CommitCollection;
use App\Domain\Gitlab\Commit\CommitFactory;
use App\Domain\Gitlab\Commit\Repository\GitlabSourceCommitRepositoryInterface;

final readonly class GitlabApiCommitRepository implements GitlabSourceCommitRepositoryInterface
{
    public function __construct(
        private GitlabApiClientCommitInterface $client,
        private CommitFactory $commitFactory,
    ) {
    }

    public function get(int $projectId, array $params = []): CommitCollection
    {
        $data = $this->client->getProjectRepositoryCommits($projectId, $params);
        $collection = new CommitCollection();

        foreach ($data as $item) {
            $commit = $this->commitFactory->create($projectId, $item);
            $collection->add($commit);
        }

        return $collection;
    }
}
