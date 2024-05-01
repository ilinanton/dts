<?php

namespace App\Application\GitLab;

use App\Application\UseCaseInterface;
use App\Domain\GitLab\Common\Repository\GitLabApiInterface;

class SyncGitLabUsers implements UseCaseInterface
{
    private GitLabApiInterface $gitLabApi;

    public function __construct(GitLabApiInterface $gitLabApi)
    {
        $this->gitLabApi = $gitLabApi;
    }

    public function execute(): void
    {
        $data = $this->gitLabApi->getGroupMembers();
        var_dump($data);
    }
}
