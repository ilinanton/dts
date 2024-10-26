<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\Event\Repository;

use App\Domain\Gitlab\Event\Event;

interface GitlabDataBaseEventRepositoryInterface
{
    public function save(Event $object): void;
}
