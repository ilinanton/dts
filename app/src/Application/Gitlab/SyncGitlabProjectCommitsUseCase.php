<?php

declare(strict_types=1);

namespace App\Application\Gitlab;

use App\Application\SyncOutputInterface;
use App\Application\UseCaseInterface;
use App\Domain\Git\Commit\CommitSinceDate;
use App\Domain\Git\Common\GitRepositoryInterface;
use App\Domain\Gitlab\Commit\CommitFactory;
use App\Domain\Gitlab\Commit\Repository\GitlabStorageCommitRepositoryInterface;
use App\Domain\Git\Project\Project as GitProject;
use App\Domain\Gitlab\Project\Repository\GitlabStorageProjectRepositoryInterface;

final readonly class SyncGitlabProjectCommitsUseCase implements UseCaseInterface
{
    public function __construct(
        private CommitSinceDate $syncDateAfter,
        private GitRepositoryInterface $gitRepository,
        private GitlabStorageProjectRepositoryInterface $dataBaseProjectRepository,
        private GitlabStorageCommitRepositoryInterface $dataBaseCommitRepository,
        private CommitFactory $commitFactory,
        private SyncOutputInterface $output,
    ) {
    }

    public function execute(): void
    {
        $this->output->writeLine('Load project commit stats after ' . $this->syncDateAfter->getValueInMainFormat());
        $gitProjectCollection = $this->gitRepository->getProjects();
        foreach ($gitProjectCollection as $project) {
            $this->syncProject($project);
        }
    }

    private function syncProject(GitProject $gitProject): void
    {
        $this->output->write(' - Git project ' . $gitProject->name->value);
        $gitlabProjectCollection = $this->dataBaseProjectRepository->findByUrlToRepo($gitProject->url->value);

        if (0 === $gitlabProjectCollection->count()) {
            $this->output->writeLine(' gitlab projects not found!');
            return;
        }

        $gitCommitCollection = $this->gitRepository->getCommits($gitProject, $this->syncDateAfter);
        $counter = 0;
        foreach ($gitCommitCollection as $gitCommit) {
            $counter++;
            foreach ($gitlabProjectCollection as $gitlabProject) {
                $commit = $this->commitFactory->create(
                    $gitlabProject->id->value,
                    $gitCommit->id->value,
                    $gitCommit->authorEmail->value,
                    $gitCommit->authorDate->getValue(),
                    $gitCommit->stats->value->files->value,
                    $gitCommit->stats->value->additions->value,
                    $gitCommit->stats->value->deletions->value,
                );
                $this->dataBaseCommitRepository->save($commit);
            }

            if (0 === $counter % 500) {
                $this->output->write(' .');
            }
        }
        $this->output->writeLine(' done');
    }
}
