<?php

namespace App\Domain\GitLab\User\Repository;

use App\Domain\GitLab\User\User;
use App\Domain\GitLab\User\UserCollection;

interface GitLabDataBaseUserRepositoryInterface
{
    public function save(User $object): void;

    public function getAll(): UserCollection;
}
