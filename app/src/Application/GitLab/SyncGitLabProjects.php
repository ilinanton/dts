<?php

namespace App\Application\GitLab;

use App\Application\UseCaseInterface;
use App\Domain\GitLab\Common\Repository\GitLabApiRepositoryInterface;

class SyncGitLabProjects implements UseCaseInterface
{
    private GitLabApiRepositoryInterface $gitLabApi;

    public function __construct(GitLabApiRepositoryInterface $gitLabApi)
    {
        $this->gitLabApi = $gitLabApi;
    }

    public function execute(): void
    {
        $data = $this->gitLabApi->getGroupProjects();
        var_dump($data);
    }
}
