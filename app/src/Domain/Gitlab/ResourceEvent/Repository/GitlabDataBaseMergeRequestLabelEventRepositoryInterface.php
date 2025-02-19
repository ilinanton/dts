<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\ResourceEvent\Repository;

use App\Domain\Gitlab\ResourceEvent\MergeRequestLabelEvent;

interface GitlabDataBaseMergeRequestLabelEventRepositoryInterface
{
    public function save(MergeRequestLabelEvent $object): void;
}
