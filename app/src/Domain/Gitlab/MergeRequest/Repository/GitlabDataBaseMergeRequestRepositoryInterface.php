<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\MergeRequest\Repository;

use App\Domain\Gitlab\Common\SyncDateAfter;
use App\Domain\Gitlab\MergeRequest\MergeRequest;
use App\Domain\Gitlab\MergeRequest\MergeRequestCollection;

interface GitlabDataBaseMergeRequestRepositoryInterface
{
    public function save(MergeRequest $object): void;

    public function getAll(): MergeRequestCollection;

    public function getUpdatedAfter(SyncDateAfter $date): MergeRequestCollection;
}
