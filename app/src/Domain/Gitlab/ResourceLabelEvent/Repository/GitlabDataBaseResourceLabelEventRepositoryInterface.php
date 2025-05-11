<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\ResourceLabelEvent\Repository;

use App\Domain\Gitlab\ResourceLabelEvent\ResourceLabelEvent;

interface GitlabDataBaseResourceLabelEventRepositoryInterface
{
    public function save(ResourceLabelEvent $object): void;
}
