<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\ResourceLabelEvent\Repository;

use App\Domain\Gitlab\ResourceLabelEvent\ResourceLabelEventCollection;

interface GitlabSourceResourceLabelEventRepositoryInterface
{
    public function getMergeRequestLabelEvents(
        int $projectId,
        int $mergeRequestIid,
        array $params = [],
    ): ResourceLabelEventCollection;
}
