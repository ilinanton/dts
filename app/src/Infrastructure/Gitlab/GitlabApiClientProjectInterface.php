<?php

declare(strict_types=1);

namespace App\Infrastructure\Gitlab;

interface GitlabApiClientProjectInterface
{
    public function getGroupProjects(array $params = []): array;
}
