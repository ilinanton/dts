<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\Project\Factory;

use App\Domain\Gitlab\Project\Project;
use App\Domain\Gitlab\Project\ValueObject\ProjectDefaultBranch;
use App\Domain\Gitlab\Project\ValueObject\ProjectHttpUrlToRepoRequired;
use App\Domain\Gitlab\Project\ValueObject\ProjectId;
use App\Domain\Gitlab\Project\ValueObject\ProjectName;
use App\Domain\Gitlab\Project\ValueObject\ProjectRequiredWebUrl;
use App\Domain\Gitlab\Project\ValueObject\ProjectSshUrlToRepo;

final class ProjectFromArray
{
    public function __construct(
        private array $data,
    ) {
    }

    public function create(): Project
    {
        return new Project(
            new ProjectId($this->data['id'] ?? 0),
            new ProjectName($this->data['name'] ?? ''),
            new ProjectDefaultBranch($this->data['default_branch'] ?? ''),
            new ProjectSshUrlToRepo($this->data['ssh_url_to_repo'] ?? ''),
            new ProjectHttpUrlToRepoRequired($this->data['http_url_to_repo'] ?? ''),
            new ProjectRequiredWebUrl($this->data['web_url'] ?? ''),
        );
    }
}
