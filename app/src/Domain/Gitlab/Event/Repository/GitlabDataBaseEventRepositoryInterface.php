<?php

namespace App\Domain\Gitlab\Event\Repository;

use App\Domain\Gitlab\Event\Event;

interface GitlabDataBaseEventRepositoryInterface
{
    public function save(Event $object): void;
}
