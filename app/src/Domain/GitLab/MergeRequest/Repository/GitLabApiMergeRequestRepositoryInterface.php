<?php

namespace App\Domain\GitLab\MergeRequest\Repository;

use App\Domain\GitLab\MergeRequest\MergeRequestCollection;

interface GitLabApiMergeRequestRepositoryInterface
{
    public function get(int $projectId, array $params = []): MergeRequestCollection;
}
