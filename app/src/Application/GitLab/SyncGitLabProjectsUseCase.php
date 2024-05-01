<?php

namespace App\Application\GitLab;

use App\Application\UseCaseInterface;
use App\Domain\GitLab\Project\Repository\GitLabApiProjectRepositoryInterface;

final class SyncGitLabProjectsUseCase implements UseCaseInterface
{
    private GitLabApiProjectRepositoryInterface $gitLabApiProjectRepository;

    public function __construct(GitLabApiProjectRepositoryInterface $gitLabApiProjectRepository)
    {
        $this->gitLabApiProjectRepository = $gitLabApiProjectRepository;
    }

    public function execute(): void
    {
        $projectCollection = $this->gitLabApiProjectRepository->get();
        var_dump($projectCollection);
    }
}
