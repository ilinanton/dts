<?php

namespace App\Domain\Gitlab\Event\Repository;

use App\Domain\Gitlab\Event\EventCollection;

interface GitlabApiEventRepositoryInterface
{
    public function getByProjectId(int $projectId, array $params = []): EventCollection;
    public function getByUserId(int $userId, array $params = []): EventCollection;
}
