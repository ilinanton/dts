<?php

namespace App\Domain\Gitlab\Project;

use App\Domain\Common\AbstractEntity;
use App\Domain\Gitlab\Project\ValueObject\ProjectDefaultBranch;
use App\Domain\Gitlab\Project\ValueObject\ProjectId;
use App\Domain\Gitlab\Project\ValueObject\ProjectName;
use App\Domain\Gitlab\Project\ValueObject\ProjectWebUrl;

final readonly class Project extends AbstractEntity
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

    public function getId(): ProjectId
    {
        return $this->id;
    }

    public function getName(): ProjectName
    {
        return $this->name;
    }

    public function getDefaultBranch(): ProjectDefaultBranch
    {
        return $this->defaultBranch;
    }

    public function getWebUrl(): ProjectWebUrl
    {
        return $this->webUrl;
    }
}
