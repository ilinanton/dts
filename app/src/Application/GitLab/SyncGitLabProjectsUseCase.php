<?php

namespace App\Application\GitLab;

use App\Application\UseCaseInterface;
use App\Domain\GitLab\Project\Repository\GitLabApiProjectRepositoryInterface;

class SyncGitLabProjectsUseCase implements UseCaseInterface
{
    private GitLabApiProjectRepositoryInterface $gitLabApiProjectRepository;

    public function __construct(GitLabApiProjectRepositoryInterface $gitLabApiProjectRepository)
    {
        $this->gitLabApiProjectRepository = $gitLabApiProjectRepository;
    }

    public function execute(): void
    {
        $data = $this->gitLabApiProjectRepository->get();
        var_dump($data);
    }
}
