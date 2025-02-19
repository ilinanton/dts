<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\Label\Repository;

use App\Domain\Gitlab\Label\Label;

interface GitlabDataBaseLabelRepositoryInterface
{
    public function save(Label $object): void;
}
