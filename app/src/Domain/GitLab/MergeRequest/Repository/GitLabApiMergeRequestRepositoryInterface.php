<?php

namespace App\Domain\GitLab\MergeRequest\Repository;

use App\Domain\GitLab\MergeRequest\MergeRequestCollection;

interface GitLabApiMergeRequestRepositoryInterface
{
    public function get(int $page = 1, int $perPage = 20): MergeRequestCollection;
}
