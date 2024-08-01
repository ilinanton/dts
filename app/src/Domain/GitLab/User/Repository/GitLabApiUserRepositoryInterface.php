<?php

namespace App\Domain\GitLab\User\Repository;

use App\Domain\GitLab\User\UserCollection;

interface GitLabApiUserRepositoryInterface
{
    public function get(array $params = []): UserCollection;
}
