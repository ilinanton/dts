<?php

namespace App\Domain\Gitlab\Project;

use App\Domain\Common\AbstractEntity;
use App\Domain\Gitlab\Project\ValueObject\ProjectDefaultBranch;
use App\Domain\Gitlab\Project\ValueObject\ProjectHttpUrlToRepo;
use App\Domain\Gitlab\Project\ValueObject\ProjectId;
use App\Domain\Gitlab\Project\ValueObject\ProjectName;
use App\Domain\Gitlab\Project\ValueObject\ProjectSshUrlToRepo;
use App\Domain\Gitlab\Project\ValueObject\ProjectWebUrl;

final readonly class Project extends AbstractEntity
{
    public function __construct(
        public ProjectId $id,
        public ProjectName $name,
        public ProjectDefaultBranch $defaultBranch,
        public ProjectSshUrlToRepo $sshUrlToRepo,
        public ProjectHttpUrlToRepo $httpUrlToRepo,
        public ProjectWebUrl $webUrl,
    ) {
    }
}
