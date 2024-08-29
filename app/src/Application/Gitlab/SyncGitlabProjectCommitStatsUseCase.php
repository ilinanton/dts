<?php

namespace App\Application\Gitlab;

use App\Application\UseCaseInterface;
use App\Domain\Gitlab\CommitStats\Repository\GitlabDataBaseCommitStatsRepositoryInterface;
use App\Domain\Gitlab\Project\Project;
use App\Domain\Gitlab\Project\Repository\GitlabDataBaseProjectRepositoryInterface;

final readonly class SyncGitlabProjectCommitStatsUseCase implements UseCaseInterface
{
    public function __construct(
        private string $syncDateAfter,
        private GitlabDataBaseProjectRepositoryInterface $gitlabDataBaseProjectRepository,
        private GitlabDataBaseCommitStatsRepositoryInterface $gitlabDataBaseCommitStatsRepository,
    ) {
    }

    public function execute(): void
    {
        echo 'Load project commits that made after ' . $this->syncDateAfter . PHP_EOL;
        $projectCollection = $this->gitlabDataBaseProjectRepository->getAll();
        foreach ($projectCollection as $project) {
            $this->syncProject($project);
        }
    }

    private function syncProject(Project $project): void
    {
        $projectId = $project->getId()->getValue();
        $projectName = $project->getName()->getValue();
        $refName = $project->getDefaultBranch();
        echo ' - #' . $projectId . ' ' . $projectName;
    }
}
