<?php

namespace App\Domain\GitLab\Commit\Repository;

use App\Domain\GitLab\Commit\Commit;

interface GitLabDataBaseCommitRepositoryInterface
{
    public function save(Commit $object): void;
}
