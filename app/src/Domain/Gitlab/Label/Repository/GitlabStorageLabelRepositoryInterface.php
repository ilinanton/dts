<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\Label\Repository;

use App\Domain\Gitlab\Label\Label;

interface GitlabStorageLabelRepositoryInterface
{
    public function save(Label $object): void;
}
