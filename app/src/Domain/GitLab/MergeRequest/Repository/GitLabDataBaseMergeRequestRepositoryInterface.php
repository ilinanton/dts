<?php

namespace App\Domain\GitLab\MergeRequest\Repository;

use App\Domain\GitLab\MergeRequest\MergeRequest;

interface GitLabDataBaseMergeRequestRepositoryInterface
{
    public function save(MergeRequest $object): void;
}
