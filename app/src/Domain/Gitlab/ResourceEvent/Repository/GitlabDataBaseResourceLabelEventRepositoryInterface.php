<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\ResourceEvent\Repository;

use App\Domain\Gitlab\ResourceEvent\ResourceLabelEvent;

interface GitlabDataBaseResourceLabelEventRepositoryInterface
{
    public function save(ResourceLabelEvent $object): void;
}
