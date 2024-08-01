<?php

namespace App\Domain\GitLab\Project\Repository;

use App\Domain\GitLab\Project\Project;
use App\Domain\GitLab\Project\ProjectCollection;

interface GitLabDataBaseProjectRepositoryInterface
{
    public function save(Project $object): void;
    public function getAll(): ProjectCollection;
}
