<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\ResourceEvent\Repository;

use App\Domain\Gitlab\ResourceEvent\MergeRequestLabelEventCollection;

interface GitlabApiMergeRequestLabelEventRepositoryInterface
{
    public function get(array $params = []): MergeRequestLabelEventCollection;
}
