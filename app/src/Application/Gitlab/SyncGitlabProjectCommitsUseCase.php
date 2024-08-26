<?php

namespace App\Application\Gitlab;

use App\Application\UseCaseInterface;
use App\Domain\Gitlab\Commit\Repository\GitlabApiCommitRepositoryInterface;
use App\Domain\Gitlab\Commit\Repository\GitlabDataBaseCommitRepositoryInterface;
use App\Domain\Gitlab\Project\Project;
use App\Domain\Gitlab\Project\Repository\GitlabDataBaseProjectRepositoryInterface;

final readonly class SyncGitlabProjectCommitsUseCase implements UseCaseInterface
{
    private const COUNT_ITEMS_PER_PAGE = 40;

    public function __construct(
        private string $syncDateAfter,
        private GitlabDataBaseProjectRepositoryInterface $gitlabDataBaseProjectRepository,
        private GitlabApiCommitRepositoryInterface $gitlabApiCommitRepository,
        private GitlabDataBaseCommitRepositoryInterface $gitlabDataBaseCommitRepository,
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

            $commitCollection = $this->gitlabApiCommitRepository->get($projectId, $params);

            foreach ($commitCollection as $commit) {
                $this->gitlabDataBaseCommitRepository->save($commit);
            }
            echo ' .';
        } while (self::COUNT_ITEMS_PER_PAGE === count($commitCollection));
        echo ' done ' . PHP_EOL;
    }
}
