<?php

namespace App\Domain\GitLab\Project\Repository;

use App\Domain\GitLab\Project\Project;

interface GitLabDataBaseProjectRepositoryInterface
{
    public function save(Project $project): void;
}
