<?php

declare(strict_types=1);

namespace App\Infrastructure\Gitlab;

use App\Domain\Gitlab\Commit\CommitCollection;
use App\Domain\Gitlab\Commit\CommitFactory;
use App\Domain\Gitlab\Commit\Repository\GitlabApiCommitRepositoryInterface;
use App\Domain\Gitlab\Common\Repository\GitlabApiClientInterface;

final readonly class GitlabApiCommitRepository implements GitlabApiCommitRepositoryInterface
{
    public function __construct(
        private GitlabApiClientInterface $client,
        private CommitFactory $commitFactory,
    ) {
    }

    public function get(int $projectId, array $params = []): CommitCollection
    {
        $data = $this->client->getProjectRepositoryCommits($projectId, $params);
        $commitCollection = new CommitCollection();

        foreach ($data as $item) {
            $commit = $this->commitFactory->create($projectId, $item);
            $commitCollection->add($commit);
        }

        return $commitCollection;
    }
}
