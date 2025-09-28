<?php

declare(strict_types=1);

namespace App\Application\Gitlab;

use App\Application\UseCaseInterface;
use App\Domain\Gitlab\MergeRequest\Repository\GitlabDataBaseMergeRequestRepositoryInterface;
use App\Domain\Gitlab\ResourceLabelEvent\Repository\GitlabApiResourceLabelEventRepositoryInterface;
use App\Domain\Gitlab\ResourceLabelEvent\Repository\GitlabDataBaseResourceLabelEventRepositoryInterface;

final readonly class SyncGitlabMergeRequestLabelEventsUseCase implements UseCaseInterface
{
    private const int COUNT_ITEMS_PER_PAGE = 60;

    public function __construct(
        private string $syncDateAfter,
        private GitlabApiResourceLabelEventRepositoryInterface $apiResourceLabelEventRepository,
        private GitlabDataBaseResourceLabelEventRepositoryInterface $databaseResourceLabelEventRepository,
        private GitlabDataBaseMergeRequestRepositoryInterface $dataBaseMergeRequestRepository,
    ) {
    }

    public function execute(): void
    {
        echo 'Load label events that created after ' . $this->syncDateAfter . PHP_EOL;
        $mergeRequestCollection = $this->dataBaseMergeRequestRepository->getAll();
        foreach ($mergeRequestCollection as $mergeRequest) {
            $page = 0;
            $projectId = $mergeRequest->projectId->value;
            $mergeRequestIid = $mergeRequest->iid->value;
            echo ' - # project_id: ' . $projectId . ' merge_request_iid: ' . $mergeRequestIid;
            do {
                ++$page;

                $resourceLabelEventCollection = $this->apiResourceLabelEventRepository->getMergeRequestLabelEvents(
                    $projectId,
                    $mergeRequestIid,
                    [
                        'page' => $page,
                        'per_page' => self::COUNT_ITEMS_PER_PAGE,
                        'created_after' => $this->syncDateAfter,
                    ],
                );

                foreach ($resourceLabelEventCollection as $resourceLabelEvent) {
                    $this->databaseResourceLabelEventRepository->save($resourceLabelEvent);
                }
                echo ' .';
            } while (self::COUNT_ITEMS_PER_PAGE === count($mergeRequestCollection));
            echo ' done ' . PHP_EOL;
        }
    }
}
