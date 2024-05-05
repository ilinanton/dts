<?php

namespace App\Application\GitLab;

use App\Application\UseCaseInterface;
use App\Domain\GitLab\Project\Repository\GitLabApiProjectRepositoryInterface;
use App\Domain\GitLab\Project\Repository\GitLabDataBaseProjectRepositoryInterface;

final class SyncGitLabProjectsUseCase implements UseCaseInterface
{
    private const COUNT_PROJECTS_PER_PAGE = 20;

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
        $page = 0;
        do {
            ++$page;
            $projectCollection = $this->gitLabApiProjectRepository->get($page, self::COUNT_PROJECTS_PER_PAGE);
            foreach ($projectCollection as $project) {
                $this->gitLabDataBaseProjectRepository->save($project);
            }
        } while (self::COUNT_PROJECTS_PER_PAGE === count($projectCollection));
    }
}
