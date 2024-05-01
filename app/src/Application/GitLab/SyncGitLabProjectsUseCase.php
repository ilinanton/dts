<?php

namespace App\Application\GitLab;

use App\Application\UseCaseInterface;
use App\Domain\GitLab\Project\Repository\GitLabApiProjectRepositoryInterface;
use App\Domain\GitLab\Project\Repository\GitLabDataBaseProjectRepositoryInterface;

final class SyncGitLabProjectsUseCase implements UseCaseInterface
{
    private GitLabApiProjectRepositoryInterface $gitLabApiProjectRepository;
    private GitLabDataBaseProjectRepositoryInterface $gitLabDataBaseProjectRepository;

    public function __construct(
        GitLabApiProjectRepositoryInterface $gitLabApiProjectRepository,
        GitLabDataBaseProjectRepositoryInterface $gitLabDataBaseProjectRepository,
    ) {
        $this->gitLabApiProjectRepository = $gitLabApiProjectRepository;
        $this->gitLabDataBaseProjectRepository = $gitLabDataBaseProjectRepository;
    }

    public function execute(): void
    {
        $projectCollection = $this->gitLabApiProjectRepository->get();
        var_dump($projectCollection);
    }
}
