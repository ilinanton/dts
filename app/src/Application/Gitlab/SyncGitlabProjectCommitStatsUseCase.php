<?php

namespace App\Application\Gitlab;

use App\Application\UseCaseInterface;
use App\Domain\Git\Common\GitRepositoryInterface;
use App\Domain\Gitlab\CommitStats\Repository\GitlabDataBaseCommitStatsRepositoryInterface;
use App\Domain\Git\Project\Project as GitProject;
use App\Domain\Gitlab\Project\Project as GitlabProject;
use App\Domain\Gitlab\Project\Repository\GitlabDataBaseProjectRepositoryInterface;

final readonly class SyncGitlabProjectCommitStatsUseCase implements UseCaseInterface
{
    public function __construct(
        private string $syncDateAfter,
        private GitRepositoryInterface $gitRepository,
        private GitlabDataBaseProjectRepositoryInterface $gitlabDataBaseProjectRepository,
        private GitlabDataBaseCommitStatsRepositoryInterface $gitlabDataBaseCommitStatsRepository,
    ) {
    }

    public function execute(): void
    {
        echo 'Load project commit stats after ' . $this->syncDateAfter . PHP_EOL;
        $gitProjectCollection = $this->gitRepository->getProjects();
        foreach ($gitProjectCollection as $project) {
            $this->syncProject($project);
        }
    }

    private function syncProject(GitProject $gitProject): void
    {
        echo ' - ' . $gitProject->name->getValue();
        $gitlabProjectCollection = $this->gitlabDataBaseProjectRepository->findByUrlToRepo($gitProject->url->getValue());
        if (0 === $gitlabProjectCollection->count()) {
            echo ' gitlab projects not found!' . PHP_EOL;
            return;
        }

        $gitCommitCollection = $this->gitRepository->getCommits($gitProject, $this->syncDateAfter);

        echo PHP_EOL;
    }
}
