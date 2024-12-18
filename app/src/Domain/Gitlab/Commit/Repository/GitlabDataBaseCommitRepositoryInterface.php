<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\Commit\Repository;

use App\Domain\Gitlab\Commit\Commit;

interface GitlabDataBaseCommitRepositoryInterface
{
    public function save(Commit $object): void;
}
