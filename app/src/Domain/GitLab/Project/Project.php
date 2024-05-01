<?php

namespace App\Domain\GitLab\Project;

use App\Domain\GitLab\Common\AbstractEntity;
use App\Domain\GitLab\Project\ValueObject\ProjectDefaultBranch;
use App\Domain\GitLab\Project\ValueObject\ProjectId;
use App\Domain\GitLab\Project\ValueObject\ProjectName;
use App\Domain\GitLab\Project\ValueObject\ProjectWebUrl;

final class Project extends AbstractEntity
{
    private ProjectId $id;
    private ProjectName $name;
    private ProjectDefaultBranch $defaultBranch;
    private ProjectWebUrl $webUrl;

    public function __construct(
        ProjectId $id,
        ProjectName $name,
        ProjectDefaultBranch $defaultBranch,
        ProjectWebUrl $webUrl,
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->defaultBranch = $defaultBranch;
        $this->webUrl = $webUrl;
    }
}
