<?php

declare(strict_types=1);

namespace App\Domain\Git\Project;

use App\Domain\Git\Project\ValueObject\ProjectBranch;
use App\Domain\Git\Project\ValueObject\ProjectName;
use App\Domain\Git\Project\ValueObject\ProjectPath;
use App\Domain\Git\Project\ValueObject\ProjectUrl;

final class ProjectFactory
{
    public function create(array $data): Project
    {
        return new Project(
            new ProjectName($data['name'] ?? ''),
            new ProjectBranch($data['branch'] ?? ''),
            new ProjectUrl($data['url'] ?? ''),
            new ProjectPath($data['path'] ?? ''),
        );
    }
}
