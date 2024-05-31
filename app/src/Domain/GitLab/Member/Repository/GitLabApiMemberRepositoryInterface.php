<?php

namespace App\Domain\GitLab\Member\Repository;

use App\Domain\GitLab\Member\MemberCollection;

interface GitLabApiMemberRepositoryInterface
{
    public function get(int $page = 1, int $perPage = 20): MemberCollection;
}
