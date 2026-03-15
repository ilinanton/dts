<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\Source;

interface GitlabSourceMergeRequestInterface
{
    public function getProjectMergeRequests(int $projectId, array $params = []): array;
}
