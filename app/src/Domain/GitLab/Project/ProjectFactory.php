<?php

namespace App\Domain\GitLab\Project;

use App\Domain\GitLab\Project\ValueObject\ProjectDefaultBranch;
use App\Domain\GitLab\Project\ValueObject\ProjectId;
use App\Domain\GitLab\Project\ValueObject\ProjectName;
use App\Domain\GitLab\Project\ValueObject\ProjectWebUrl;

class ProjectFactory
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