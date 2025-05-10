<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\ResourceEvent\Repository;

use App\Domain\Gitlab\ResourceEvent\ResourceLabelEventCollection;

interface GitlabApiMergeRequestLabelEventRepositoryInterface
{
    public function get(array $params = []): ResourceLabelEventCollection;
}
