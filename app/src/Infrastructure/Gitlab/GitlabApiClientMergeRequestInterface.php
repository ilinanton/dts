<?php

declare(strict_types=1);

namespace App\Infrastructure\Gitlab;

interface GitlabApiClientMergeRequestInterface
{
    public function getProjectMergeRequests(int $projectId, array $params = []): array;
}
