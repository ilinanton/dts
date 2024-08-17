<?php

namespace App\Infrastructure\GitLab;

use App\Domain\GitLab\Commit\CommitCollection;
use App\Domain\GitLab\Commit\CommitFactory;
use App\Domain\GitLab\Commit\Repository\GitLabApiCommitRepositoryInterface;
use App\Domain\GitLab\Common\Repository\GitLabApiClientInterface;

final readonly class GitLabApiCommitRepository implements GitLabApiCommitRepositoryInterface
{
    public function __construct(
        private GitLabApiClientInterface $client,
        private CommitFactory $commitFactory,
    ) {
    }

    public function get(int $projectId, array $params = []): CommitCollection
    {
        $data = $this->client->getProjectRepositoryCommits($projectId, $params);
        $commitCollection = new CommitCollection();

        foreach ($data as $item) {
            $commit = $this->commitFactory->create($item);
            $commitCollection->add($commit);
        }

        return $commitCollection;
    }
}
