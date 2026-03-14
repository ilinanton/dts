<?php

declare(strict_types=1);

namespace App\Application\Gitlab;

use App\Application\Common\Paginator;
use App\Application\SyncOutputInterface;
use App\Application\UseCaseInterface;
use App\Domain\Gitlab\Commit\CommitCollection;
use App\Domain\Gitlab\Common\SyncDateAfter;
use App\Domain\Gitlab\Commit\Repository\GitlabApiCommitRepositoryInterface;
use App\Domain\Gitlab\Commit\Repository\GitlabDataBaseCommitRepositoryInterface;
use App\Domain\Gitlab\Project\Project;
use App\Domain\Gitlab\Project\Repository\GitlabDataBaseProjectRepositoryInterface;

final readonly class SyncGitlabProjectCommitsUseCase implements UseCaseInterface
{
    public function __construct(
        private SyncDateAfter $syncDateAfter,
        private GitlabDataBaseProjectRepositoryInterface $dataBaseProjectRepository,
        private GitlabApiCommitRepositoryInterface $apiCommitRepository,
        private GitlabDataBaseCommitRepositoryInterface $dataBaseCommitRepository,
        private SyncOutputInterface $output,
        private Paginator $paginator,
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

        $baseParams = [
            'since' => $this->syncDateAfter->getValueInMainFormat(),
            'ref_name' => $refName,
            'with_stats' => true,
        ];

        $items = $this->paginator->paginate(
            function (array $params) use ($projectId): CommitCollection {
                $this->output->write(' .');
                return $this->apiCommitRepository->get($projectId, $params);
            },
            $baseParams,
        );

        foreach ($items as $commit) {
            $this->dataBaseCommitRepository->save($commit);
        }
        $this->output->writeLine(' done');
    }
}
