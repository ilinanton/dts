<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\MergeRequest\Repository;

use App\Domain\Gitlab\MergeRequest\MergeRequestCollection;

interface GitlabSourceMergeRequestRepositoryInterface
{
    public function get(int $projectId, array $params = []): MergeRequestCollection;
}
