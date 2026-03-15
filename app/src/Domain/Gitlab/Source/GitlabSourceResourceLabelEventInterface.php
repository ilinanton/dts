<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\Source;

interface GitlabSourceResourceLabelEventInterface
{
    public function getMergeRequestLabelEvents(int $projectId, int $mergeRequestIid, array $params = []): array;
}
