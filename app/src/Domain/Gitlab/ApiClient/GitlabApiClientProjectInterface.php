<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\ApiClient;

interface GitlabApiClientProjectInterface
{
    public function getGroupProjects(array $params = []): array;
}
