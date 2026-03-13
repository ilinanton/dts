<?php

declare(strict_types=1);

namespace App\Application\Gitlab;

use App\Application\SyncOutputInterface;
use App\Application\UseCaseInterface;
use App\Domain\Gitlab\Common\ItemsPerPage;
use App\Domain\Gitlab\Common\SyncDateAfter;
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
        private ItemsPerPage $itemsPerPage,
    ) {
    }

    public function execute(): void
    {
        $this->output->writeLine('Load merge requests that created after ' . $this->syncDateAfter->getValueInMainFormat());
        $projectCollection = $this->dataBaseProjectRepository->getAll();
        foreach ($projectCollection as $project) {
            $page = 0;
            $projectId = $project->id->value;
            $projectName = $project->name->value;
            $this->output->write(' - #' . $projectId . ' ' . $projectName);
            do {
                ++$page;

                $mergeRequestCollection = $this->apiMergeRequestRepository->get($projectId, [
                    'page' => $page,
                    'per_page' => $this->itemsPerPage->value,
                    'created_after' => $this->syncDateAfter->getValueInMainFormat(),
                ]);

                foreach ($mergeRequestCollection as $mergeRequest) {
                    $this->dataBaseMergeRequestRepository->save($mergeRequest);
                }
                $this->output->write(' .');
            } while ($this->itemsPerPage->value === count($mergeRequestCollection));
            $this->output->writeLine(' done');
        }
    }
}
