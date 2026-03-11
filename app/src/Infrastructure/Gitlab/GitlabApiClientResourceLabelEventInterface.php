<?php

declare(strict_types=1);

namespace App\Infrastructure\Gitlab;

interface GitlabApiClientResourceLabelEventInterface
{
    public function getMergeRequestLabelEvents(int $projectId, int $mergeRequestIid, array $params = []): array;
}
