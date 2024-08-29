<?php

namespace App\Domain\Gitlab\CommitStats\Repository;

use App\Domain\Gitlab\CommitStats\CommitStats;

interface GitlabDataBaseCommitStatsRepositoryInterface
{
    public function save(CommitStats $object): void;
}
