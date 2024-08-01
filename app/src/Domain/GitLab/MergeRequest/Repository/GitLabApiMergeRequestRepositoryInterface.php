<?php

namespace App\Domain\GitLab\MergeRequest\Repository;

use App\Domain\GitLab\MergeRequest\MergeRequestCollection;

interface GitLabApiMergeRequestRepositoryInterface
{
    public function get(array $params = []): MergeRequestCollection;
}
