<?php

declare(strict_types=1);

namespace App\Application\Gitlab;

use App\Application\UseCaseInterface;
use App\Domain\Git\Commit\CommitSinceDate;
use App\Domain\Git\Common\GitRepositoryInterface;
use App\Domain\Gitlab\CommitStats\CommitStatsFactory;
use App\Domain\Gitlab\CommitStats\Repository\GitlabDataBaseCommitStatsRepositoryInterface;
use App\Domain\Git\Project\Project as GitProject;
use App\Domain\Gitlab\Project\Repository\GitlabDataBaseProjectRepositoryInterface;

final readonly class SyncGitlabProjectCommitStatsUseCase implements UseCaseInterface
{
    public function __construct(
        private CommitSinceDate $syncDateAfter,
        private GitRepositoryInterface $gitRepository,
        private GitlabDataBaseProjectRepositoryInterface $dataBaseProjectRepository,
        private GitlabDataBaseCommitStatsRepositoryInterface $dataBaseCommitStatsRepository,
        private CommitStatsFactory $commitStatsFactory,
    ) {
    }

    public function execute(): void
    {
        echo 'Load project commit stats after ' . $this->syncDateAfter->getValueInMainFormat() . PHP_EOL;
        $gitProjectCollection = $this->gitRepository->getProjects();
        foreach ($gitProjectCollection as $project) {
            $this->syncProject($project);
        }
    }

    private function syncProject(GitProject $gitProject): void
    {
        echo ' - Git project ' . $gitProject->name->value;
        $gitlabProjectCollection =
            $this->dataBaseProjectRepository->findByUrlToRepo($gitProject->url->value);

        if (0 === $gitlabProjectCollection->count()) {
            echo ' gitlab projects not found!' . PHP_EOL;
            return;
        }

        $gitCommitCollection = $this->gitRepository->getCommits($gitProject, $this->syncDateAfter);
        $counter = 0;
        foreach ($gitCommitCollection as $gitCommit) {
            $counter++;
            foreach ($gitlabProjectCollection as $gitlabProject) {
                $gitlabCommitStats = $this->commitStatsFactory->create(
                    $gitlabProject->id->value,
                    $gitCommit->id->value,
                    $gitCommit->stats->value,
                );
                $this->dataBaseCommitStatsRepository->save($gitlabCommitStats);
            }

            if (0 === $counter % 500) {
                echo ' .';
            }
        }
        echo ' done ' . PHP_EOL;
    }
}
