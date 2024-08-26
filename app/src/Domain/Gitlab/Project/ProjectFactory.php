<?php

namespace App\Domain\Gitlab\Project;

use App\Domain\Gitlab\Project\ValueObject\ProjectDefaultBranch;
use App\Domain\Gitlab\Project\ValueObject\ProjectId;
use App\Domain\Gitlab\Project\ValueObject\ProjectName;
use App\Domain\Gitlab\Project\ValueObject\ProjectWebUrl;

final class ProjectFactory
{
    public function create(array $data): Project
    {
        return new Project(
            new ProjectId($data['id'] ?? 0),
            new ProjectName($data['name'] ?? ''),
            new ProjectDefaultBranch($data['default_branch'] ?? ''),
            new ProjectWebUrl($data['web_url'] ?? ''),
        );
    }
}
