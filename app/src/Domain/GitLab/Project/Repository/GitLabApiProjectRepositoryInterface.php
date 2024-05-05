<?php

namespace App\Domain\GitLab\Project\Repository;

use App\Domain\GitLab\Project\ProjectCollection;

interface GitLabApiProjectRepositoryInterface
{
    public function get(int $page = 1, int $perPage = 20): ProjectCollection;
}
