<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\Source;

interface GitlabSourceCommitInterface
{
    public function getProjectRepositoryCommits(int $projectId, array $params = []): array;
}
