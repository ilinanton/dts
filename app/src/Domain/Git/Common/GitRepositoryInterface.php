<?php

declare(strict_types=1);

namespace App\Domain\Git\Common;

use App\Domain\Git\Commit\CommitCollection;
use App\Domain\Git\Project\Project;
use App\Domain\Git\Project\ProjectCollection;

interface GitRepositoryInterface
{
    public function getProjects(): ProjectCollection;

    public function getCommits(Project $project, string $since): CommitCollection;
}
