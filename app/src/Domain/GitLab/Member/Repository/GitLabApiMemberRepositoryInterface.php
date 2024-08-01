<?php

namespace App\Domain\GitLab\Member\Repository;

use App\Domain\GitLab\Member\MemberCollection;

interface GitLabApiMemberRepositoryInterface
{
    public function get(array $params = []): MemberCollection;
}
