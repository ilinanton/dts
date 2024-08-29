<?php

namespace App\Domain\Git\Common;

use App\Domain\Git\Commit\CommitCollection;

interface GitRepositoryInterface
{
    public function getCommits(): CommitCollection;
}
