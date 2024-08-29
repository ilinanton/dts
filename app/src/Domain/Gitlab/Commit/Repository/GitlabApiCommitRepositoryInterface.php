<?php

namespace App\Domain\Gitlab\Commit\Repository;

use App\Domain\Gitlab\Commit\CommitCollection;

interface GitlabApiCommitRepositoryInterface
{
    public function get(int $projectId, array $params = []): CommitCollection;
}