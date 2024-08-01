<?php

namespace App\Domain\GitLab\Member\Repository;

use App\Domain\GitLab\Member\Member;
use App\Domain\GitLab\Member\MemberCollection;

interface GitLabDataBaseMemberRepositoryInterface
{
    public function save(Member $object): void;

    public function getAll(): MemberCollection;
}
