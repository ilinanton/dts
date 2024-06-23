<?php

namespace App\Domain\GitLab\Member\Repository;

use App\Domain\GitLab\Member\Member;

interface GitLabDataBaseMemberRepositoryInterface
{
    public function save(Member $object): void;
}
