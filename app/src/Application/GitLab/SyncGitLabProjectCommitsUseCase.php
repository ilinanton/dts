<?php

namespace App\Application\GitLab;

use App\Application\UseCaseInterface;
use App\Domain\GitLab\Commit\Repository\GitLabApiCommitRepositoryInterface;
use App\Domain\GitLab\Commit\Repository\GitLabDataBaseCommitRepositoryInterface;
use App\Domain\GitLab\Project\Project;
use App\Domain\GitLab\Project\Repository\GitLabDataBaseProjectRepositoryInterface;

final readonly class SyncGitLabProjectCommitsUseCase implements UseCaseInterface
{
    private const COUNT_ITEMS_PER_PAGE = 10;

    public function __construct(
        private string $syncDateAfter,
        private GitLabDataBaseProjectRepositoryInterface $gitLabDataBaseProjectRepository,
        private GitLabApiCommitRepositoryInterface $gitLabApiCommitRepository,
        private GitLabDataBaseCommitRepositoryInterface $gitLabDataBaseCommitRepository,
    ) {
    }

    public function execute(): void
    {
        echo 'Load project commits that made after ' . $this->syncDateAfter . PHP_EOL;
        $projectCollection = $this->gitLabDataBaseProjectRepository->getAll();
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
        $page = 0;
        do {
            ++$page;

            $params = [
                'page' => $page,
                'per_page' => self::COUNT_ITEMS_PER_PAGE,
                'since' => $this->syncDateAfter,
                'ref_name' => $refName,
                'with_stats' => true,
            ];

            $commitCollection = $this->gitLabApiCommitRepository->get($projectId, $params);

            foreach ($commitCollection as $commit) {
                $this->gitLabDataBaseCommitRepository->save($commit);
            }
            echo ' .';
        } while (self::COUNT_ITEMS_PER_PAGE === count($commitCollection));
        echo ' done ' . PHP_EOL;
    }
}
