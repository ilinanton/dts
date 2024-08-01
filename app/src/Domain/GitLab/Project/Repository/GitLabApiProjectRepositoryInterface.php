<?php

namespace App\Domain\GitLab\Project\Repository;

use App\Domain\GitLab\Project\ProjectCollection;

interface GitLabApiProjectRepositoryInterface
{
    public function get(array $params = []): ProjectCollection;
}
