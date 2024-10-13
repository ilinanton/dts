<?php

namespace App\Application\Gitlab;

use App\Application\UseCaseInterface;
use App\Domain\Git\Common\GitRepositoryInterface;
use App\Domain\Gitlab\CommitStats\CommitStatsFactory;
use App\Domain\Gitlab\CommitStats\Repository\GitlabDataBaseCommitStatsRepositoryInterface;
use App\Domain\Git\Project\Project as GitProject;
use App\Domain\Gitlab\Project\Repository\GitlabDataBaseProjectRepositoryInterface;

final readonly class SyncGitlabProjectCommitStatsUseCase implements UseCaseInterface
{
    public function __construct(
        private string $syncDateAfter,
        private GitRepositoryInterface $gitRepository,
        private GitlabDataBaseProjectRepositoryInterface $gitlabDataBaseProjectRepository,
        private CommitStatsFactory $gitlabCommitStatsFactory,
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
        echo ' - Git project ' . $gitProject->name->value;
        $gitlabProjectCollection =
            $this->gitlabDataBaseProjectRepository->findByUrlToRepo($gitProject->url->value);

        if (0 === $gitlabProjectCollection->count()) {
            echo ' gitlab projects not found!' . PHP_EOL;
            return;
        }

        $gitCommitCollection = $this->gitRepository->getCommits($gitProject, $this->syncDateAfter);
        $counter = 0;
        foreach ($gitCommitCollection as $gitCommit) {
            $counter++;
            $gitCommitStats = $gitCommit->stats->value;
            $gitStatsData = [
                'id' => $gitCommit->id->getValue(),
                'files' => $gitCommitStats->files->getValue(),
                'additions' => $gitCommitStats->additions->getValue(),
                'deletions' => $gitCommitStats->deletions->getValue(),
            ];
            foreach ($gitlabProjectCollection as $gitlabProject) {
                $gitlabCommitStats = $this->gitlabCommitStatsFactory->create(
                    $gitlabProject->id->getValue(),
                    $gitStatsData,
                );
                $this->gitlabDataBaseCommitStatsRepository->save($gitlabCommitStats);
            }

            if (0 === $counter % 500) {
                echo ' .';
            }
        }
        echo ' done ' . PHP_EOL;
    }
}
