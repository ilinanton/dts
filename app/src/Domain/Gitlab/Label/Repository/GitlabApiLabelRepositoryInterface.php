<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\Label\Repository;

interface GitlabApiLabelRepositoryInterface
{
    public function getGroupLabels(array $params = []): array;
}
