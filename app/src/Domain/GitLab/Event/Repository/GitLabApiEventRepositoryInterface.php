<?php

namespace App\Domain\GitLab\Event\Repository;

use App\Domain\GitLab\Event\EventCollection;

interface GitLabApiEventRepositoryInterface
{
    public function getByProjectId(int $projectId, array $params = []): EventCollection;
    public function getByUserId(int $userId, array $params = []): EventCollection;
}
