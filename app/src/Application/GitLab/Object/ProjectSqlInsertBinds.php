<?php

namespace App\Application\GitLab\Object;

use App\Domain\GitLab\Project\Project;

class ProjectSqlInsertBinds
{
    private Project $project;

    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    public function get(): array
    {
    }
}
