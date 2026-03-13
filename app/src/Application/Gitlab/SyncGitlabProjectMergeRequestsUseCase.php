<?php

declare(strict_types=1);

namespace App\Application\Gitlab;

use App\Application\SyncOutputInterface;
use App\Application\UseCaseInterface;
use App\Domain\Gitlab\MergeRequest\Repository\GitlabApiMergeRequestRepositoryInterface;
use App\Domain\Gitlab\MergeRequest\Repository\GitlabDataBaseMergeRequestRepositoryInterface;
use App\Domain\Gitlab\Project\Repository\GitlabDataBaseProjectRepositoryInterface;

final readonly class SyncGitlabProjectMergeRequestsUseCase implements UseCaseInterface
{
    private const int COUNT_ITEMS_PER_PAGE = 40;

    public function __construct(
        private string $syncDateAfter,
        private GitlabApiMergeRequestRepositoryInterface $apiMergeRequestRepository,
        private GitlabDataBaseMergeRequestRepositoryInterface $dataBaseMergeRequestRepository,
        private GitlabDataBaseProjectRepositoryInterface $dataBaseProjectRepository,
        private SyncOutputInterface $output,
    ) {
    }

    public function execute(): void
    {
        $this->output->writeLine('Load merge requests that created after ' . $this->syncDateAfter);
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
                    'per_page' => self::COUNT_ITEMS_PER_PAGE,
                    'created_after' => $this->syncDateAfter,
                ]);

                foreach ($mergeRequestCollection as $mergeRequest) {
                    $this->dataBaseMergeRequestRepository->save($mergeRequest);
                }
                $this->output->write(' .');
            } while (self::COUNT_ITEMS_PER_PAGE === count($mergeRequestCollection));
            $this->output->writeLine(' done');
        }
    }
}
