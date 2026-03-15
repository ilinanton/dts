<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\Source;

interface GitlabSourceProjectInterface
{
    public function getGroupProjects(array $params = []): array;
}
