<?php

namespace App\Domain\Gitlab\MergeRequest\Repository;

use App\Domain\Gitlab\MergeRequest\MergeRequestCollection;

interface GitlabApiMergeRequestRepositoryInterface
{
    public function get(int $projectId, array $params = []): MergeRequestCollection;
}
