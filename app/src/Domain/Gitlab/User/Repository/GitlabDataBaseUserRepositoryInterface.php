<?php

namespace App\Domain\Gitlab\User\Repository;

use App\Domain\Gitlab\User\User;
use App\Domain\Gitlab\User\UserCollection;

interface GitlabDataBaseUserRepositoryInterface
{
    public function save(User $object): void;

    public function getAll(): UserCollection;
}
