<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\ApiClient;

interface GitlabApiClientMergeRequestInterface
{
    public function getProjectMergeRequests(int $projectId, array $params = []): array;
}
