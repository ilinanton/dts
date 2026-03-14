<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\ApiClient;

interface GitlabApiClientCommitInterface
{
    public function getProjectRepositoryCommits(int $projectId, array $params = []): array;
}
