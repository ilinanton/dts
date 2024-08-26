<?php

namespace App\Domain\Gitlab\User\Repository;

use App\Domain\Gitlab\User\UserCollection;

interface GitlabApiUserRepositoryInterface
{
    public function get(array $params = []): UserCollection;
}
