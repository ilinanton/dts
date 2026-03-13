<?php

declare(strict_types=1);

namespace App\Application\Gitlab;

use App\Application\SyncOutputInterface;
use App\Application\UseCaseInterface;
use App\Domain\Gitlab\Common\SyncDateAfter;
use App\Domain\Gitlab\Commit\Repository\GitlabApiCommitRepositoryInterface;
use App\Domain\Gitlab\Commit\Repository\GitlabDataBaseCommitRepositoryInterface;
use App\Domain\Gitlab\Project\Project;
use App\Domain\Gitlab\Project\Repository\GitlabDataBaseProjectRepositoryInterface;

final readonly class SyncGitlabProjectCommitsUseCase implements UseCaseInterface
{
    private const int COUNT_ITEMS_PER_PAGE = 40;

    public function __construct(
        private SyncDateAfter $syncDateAfter,
        private GitlabDataBaseProjectRepositoryInterface $dataBaseProjectRepository,
        private GitlabApiCommitRepositoryInterface $apiCommitRepository,
        private GitlabDataBaseCommitRepositoryInterface $dataBaseCommitRepository,
        private SyncOutputInterface $output,
    ) {
    }

    public function execute(): void
    {
        $this->output->writeLine('Load project commits that made after ' . $this->syncDateAfter->getValueInMainFormat());
        $projectCollection = $this->dataBaseProjectRepository->getAll();
        foreach ($projectCollection as $project) {
            $this->syncProject($project);
        }
    }

    private function syncProject(Project $project): void
    {
        $projectId = $project->id->value;
        $projectName = $project->name->value;
        $refName = $project->defaultBranch->value;
        $this->output->write(' - #' . $projectId . ' ' . $projectName);
        $page = 0;
        do {
            ++$page;

            $params = [
                'page' => $page,
                'per_page' => self::COUNT_ITEMS_PER_PAGE,
                'since' => $this->syncDateAfter->getValueInMainFormat(),
                'ref_name' => $refName,
                'with_stats' => true,
            ];

            $commitCollection = $this->apiCommitRepository->get($projectId, $params);

            foreach ($commitCollection as $commit) {
                $this->dataBaseCommitRepository->save($commit);
            }
            $this->output->write(' .');
        } while (self::COUNT_ITEMS_PER_PAGE === count($commitCollection));
        $this->output->writeLine(' done');
    }
}
