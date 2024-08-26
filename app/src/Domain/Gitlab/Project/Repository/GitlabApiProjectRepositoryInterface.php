<?php

namespace App\Domain\Gitlab\Project\Repository;

use App\Domain\Gitlab\Project\ProjectCollection;

interface GitlabApiProjectRepositoryInterface
{
    public function get(array $params = []): ProjectCollection;
}
