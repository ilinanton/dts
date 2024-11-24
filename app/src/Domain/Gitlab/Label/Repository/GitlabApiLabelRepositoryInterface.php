<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\Label\Repository;

use App\Domain\Gitlab\Label\LabelCollection;

interface GitlabApiLabelRepositoryInterface
{
    public function getByGroupId(int $groupId, array $params = []): LabelCollection;

    public function getByProjectId(int $projectId, array $params = []): LabelCollection;
}
