<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\Project\Repository;

use App\Domain\Gitlab\Project\ProjectCollection;

interface GitlabSourceProjectRepositoryInterface
{
    public function get(array $params = []): ProjectCollection;
}
