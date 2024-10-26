<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\MergeRequest\Repository;

use App\Domain\Gitlab\MergeRequest\MergeRequest;

interface GitlabDataBaseMergeRequestRepositoryInterface
{
    public function save(MergeRequest $object): void;
}
