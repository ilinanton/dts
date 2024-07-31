<?php

namespace App\Domain\GitLab\Event\Repository;

use App\Domain\GitLab\Event\EventCollection;

interface GitLabApiEventRepositoryInterface
{
    public function getByProjectId(int $projectId, int $page = 1, int $perPage = 20): EventCollection;
    public function getByUserId(int $userId, int $page = 1, int $perPage = 20): EventCollection;
}
