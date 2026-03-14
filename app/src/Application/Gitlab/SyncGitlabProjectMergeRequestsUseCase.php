<?php

declare(strict_types=1);

namespace App\Application\Gitlab;

use App\Application\Common\Paginator;
use App\Application\SyncOutputInterface;
use App\Application\UseCaseInterface;
use App\Domain\Gitlab\Common\SyncDateAfter;
use App\Domain\Gitlab\MergeRequest\MergeRequestCollection;
use App\Domain\Gitlab\MergeRequest\Repository\GitlabApiMergeRequestRepositoryInterface;
use App\Domain\Gitlab\MergeRequest\Repository\GitlabDataBaseMergeRequestRepositoryInterface;
use App\Domain\Gitlab\Project\Repository\GitlabDataBaseProjectRepositoryInterface;

final readonly class SyncGitlabProjectMergeRequestsUseCase implements UseCaseInterface
{
    public function __construct(
        private SyncDateAfter $syncDateAfter,
        private GitlabApiMergeRequestRepositoryInterface $apiMergeRequestRepository,
        private GitlabDataBaseMergeRequestRepositoryInterface $dataBaseMergeRequestRepository,
        private GitlabDataBaseProjectRepositoryInterface $dataBaseProjectRepository,
        private SyncOutputInterface $output,
        private Paginator $paginator,
    ) {
    }

    public function execute(): void
    {
        $this->output->writeLine('Load merge requests that created after ' . $this->syncDateAfter->getValueInMainFormat());
        $projectCollection = $this->dataBaseProjectRepository->getAll();
        foreach ($projectCollection as $project) {
            $projectId = $project->id->value;
            $projectName = $project->name->value;
            $this->output->write(' - #' . $projectId . ' ' . $projectName);

            $baseParams = ['created_after' => $this->syncDateAfter->getValueInMainFormat()];

            $items = $this->paginator->paginate(
                function (array $params) use ($projectId): MergeRequestCollection {
                    $this->output->write(' .');
                    return $this->apiMergeRequestRepository->get($projectId, $params);
                },
                $baseParams,
            );

            foreach ($items as $mergeRequest) {
                $this->dataBaseMergeRequestRepository->save($mergeRequest);
            }
            $this->output->writeLine(' done');
        }
    }
}
