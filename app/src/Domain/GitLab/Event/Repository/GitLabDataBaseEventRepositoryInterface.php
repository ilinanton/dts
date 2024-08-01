<?php

namespace App\Domain\GitLab\Event\Repository;

use App\Domain\GitLab\Event\Event;

interface GitLabDataBaseEventRepositoryInterface
{
    public function save(Event $object): void;
}
