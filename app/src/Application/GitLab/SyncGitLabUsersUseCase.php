<?php

namespace App\Application\GitLab;

use App\Application\UseCaseInterface;
use App\Domain\GitLab\Member\Repository\GitLabApiMemberRepositoryInterface;

class SyncGitLabUsersUseCase implements UseCaseInterface
{
    private GitLabApiMemberRepositoryInterface $gitLabApi;

    public function __construct(GitLabApiMemberRepositoryInterface $gitLabApi)
    {
        $this->gitLabApi = $gitLabApi;
    }

    public function execute(): void
    {
        $data = $this->gitLabApi->get();
        var_dump($data);
    }
}
