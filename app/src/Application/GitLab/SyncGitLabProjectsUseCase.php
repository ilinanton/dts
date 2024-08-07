<?php

namespace App\Application\GitLab;

use App\Application\UseCaseInterface;
use App\Domain\GitLab\Project\Repository\GitLabApiProjectRepositoryInterface;
use App\Domain\GitLab\Project\Repository\GitLabDataBaseProjectRepositoryInterface;

final readonly class SyncGitLabProjectsUseCase implements UseCaseInterface
{
    private const COUNT_ITEMS_PER_PAGE = 20;

    public function __construct(
        private GitLabApiProjectRepositoryInterface $gitLabApiProjectRepository,
        private GitLabDataBaseProjectRepositoryInterface $gitLabDataBaseProjectRepository,
    ) {
    }

    public function execute(): void
    {
        $page = 0;
        do {
            ++$page;
            $projectCollection = $this->gitLabApiProjectRepository->get([
                'page' => $page,
                'per_page' => self::COUNT_ITEMS_PER_PAGE,
            ]);
            foreach ($projectCollection as $project) {
                echo 'Load project #' . $project->getId()->getValue() . ' ' . $project->getName()->getValue();
                $this->gitLabDataBaseProjectRepository->save($project);
                echo ' done ' . PHP_EOL;
            }
        } while (self::COUNT_ITEMS_PER_PAGE === count($projectCollection));
    }
}
