<?php

namespace App\Application\Gitlab;

use App\Application\UseCaseInterface;
use App\Domain\Gitlab\MergeRequest\Repository\GitlabApiMergeRequestRepositoryInterface;
use App\Domain\Gitlab\MergeRequest\Repository\GitlabDataBaseMergeRequestRepositoryInterface;
use App\Domain\Gitlab\Project\Repository\GitlabDataBaseProjectRepositoryInterface;

final readonly class SyncGitlabProjectMergeRequestsUseCase implements UseCaseInterface
{
    private const COUNT_ITEMS_PER_PAGE = 40;

    public function __construct(
        private string $syncDateAfter,
        private GitlabApiMergeRequestRepositoryInterface $gitlabApiMergeRequestRepository,
        private GitlabDataBaseMergeRequestRepositoryInterface $gitlabDataBaseMergeRequestRepository,
        private GitlabDataBaseProjectRepositoryInterface $gitlabDataBaseProjectRepository,
    ) {
    }

    public function execute(): void
    {
        echo 'Load merge requests that created after ' . $this->syncDateAfter . PHP_EOL;
        $projectCollection = $this->gitlabDataBaseProjectRepository->getAll();
        foreach ($projectCollection as $project) {
            $page = 0;
            $projectId = $project->id->getValue();
            $projectName = $project->name->getValue();
            echo ' - #' . $projectId . ' ' . $projectName;
            do {
                ++$page;

                $mergeRequestCollection = $this->gitlabApiMergeRequestRepository->get($projectId, [
                    'page' => $page,
                    'per_page' => self::COUNT_ITEMS_PER_PAGE,
                    'created_after' => $this->syncDateAfter,
                ]);

                foreach ($mergeRequestCollection as $mergeRequest) {
                    $this->gitlabDataBaseMergeRequestRepository->save($mergeRequest);
                }
                echo ' .';
            } while (self::COUNT_ITEMS_PER_PAGE === count($mergeRequestCollection));
            echo ' done ' . PHP_EOL;
        }
    }
}
