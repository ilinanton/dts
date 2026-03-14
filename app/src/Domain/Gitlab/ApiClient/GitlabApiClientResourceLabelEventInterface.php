<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\ApiClient;

interface GitlabApiClientResourceLabelEventInterface
{
    public function getMergeRequestLabelEvents(int $projectId, int $mergeRequestIid, array $params = []): array;
}
