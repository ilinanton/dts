<?php

namespace App\Domain\Git\Project;

use App\Domain\Git\Project\ValueObject\ProjectName;
use App\Domain\Git\Project\ValueObject\ProjectBranch;
use App\Domain\Git\Project\ValueObject\ProjectPath;
use App\Domain\Git\Project\ValueObject\ProjectUrl;

final readonly class Project
{
    public function __construct(
        public ProjectName $name,
        public ProjectBranch $branch,
        public ProjectUrl $url,
        public ProjectPath $path,
    ) {
    }
}
