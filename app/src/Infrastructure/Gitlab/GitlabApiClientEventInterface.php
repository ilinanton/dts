<?php

declare(strict_types=1);

namespace App\Infrastructure\Gitlab;

interface GitlabApiClientEventInterface
{
    public function getProjectEvents(int $projectId, array $params = []): array;

    public function getUserEvents(int $userId, array $params = []): array;
}
