<?php

namespace App\Application\Gitlab;

use App\Application\UseCaseInterface;
use App\Domain\Gitlab\Project\Repository\GitlabApiProjectRepositoryInterface;
use App\Domain\Gitlab\Project\Repository\GitlabDataBaseProjectRepositoryInterface;

final readonly class SyncGitlabProjectsUseCase implements UseCaseInterface
{
    private const COUNT_ITEMS_PER_PAGE = 60;

    public function __construct(
        private GitlabApiProjectRepositoryInterface $gitlabApiProjectRepository,
        private GitlabDataBaseProjectRepositoryInterface $gitlabDataBaseProjectRepository,
    ) {
    }

    public function execute(): void
    {
        $page = 0;
        do {
            ++$page;
            $projectCollection = $this->gitlabApiProjectRepository->get([
                'page' => $page,
                'per_page' => self::COUNT_ITEMS_PER_PAGE,
            ]);
            foreach ($projectCollection as $project) {
                echo 'Load project #' . $project->id->getValue() . ' ' . $project->name->getValue();
                $this->gitlabDataBaseProjectRepository->save($project);
                echo ' done ' . PHP_EOL;
            }
        } while (self::COUNT_ITEMS_PER_PAGE === count($projectCollection));
    }
}
