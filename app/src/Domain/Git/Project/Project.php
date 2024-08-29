<?php

namespace App\Domain\Git\Project;

use App\Domain\Git\Project\ValueObject\ProjectId;
use App\Domain\Git\Project\ValueObject\ProjectName;
use App\Domain\Git\Project\ValueObject\ProjectDefaultBranch;
use App\Domain\Git\Project\ValueObject\ProjectUrl;

final readonly class Project
{
    public function __construct(
        public ProjectId $id,
        public ProjectName $name,
        public ProjectDefaultBranch $defaultBranch,
        public ProjectUrl $url,
    ) {
    }
}
