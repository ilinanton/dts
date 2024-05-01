<?php

namespace App\Domain\GitLab\Member\Repository;

interface GitLabApiMemberRepositoryInterface
{
    public function get(): array;
}
