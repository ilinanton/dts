<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\Source;

interface GitlabSourceEventInterface
{
    public function getProjectEvents(int $projectId, array $params = []): array;

    public function getUserEvents(int $userId, array $params = []): array;
}
