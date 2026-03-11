<?php

declare(strict_types=1);

namespace App\Infrastructure\Gitlab;

interface GitlabApiClientCommitInterface
{
    public function getProjectRepositoryCommits(int $projectId, array $params = []): array;
}
