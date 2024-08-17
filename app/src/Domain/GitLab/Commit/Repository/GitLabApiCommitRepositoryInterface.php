<?php

namespace App\Domain\GitLab\Commit\Repository;

use App\Domain\GitLab\Commit\CommitCollection;

interface GitLabApiCommitRepositoryInterface
{
    public function get(int $projectId, array $params = []): CommitCollection;
}
