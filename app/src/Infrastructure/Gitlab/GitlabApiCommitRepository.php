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
    ) {
    }

    public function get(int $projectId, array $params = []): CommitCollection
    {
        $data = $this->client->getProjectRepositoryCommits($projectId, $params);
        $collection = new CommitCollection();
        $factory = new CommitFactory();

        foreach ($data as $item) {
            $commit = $factory->create($projectId, $item);
            $collection->add($commit);
        }

        return $collection;
    }
}
