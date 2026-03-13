<?php

declare(strict_types=1);

namespace App\Application\Gitlab;

use App\Application\SyncOutputInterface;
use App\Application\UseCaseInterface;
use App\Domain\Gitlab\Common\ItemsPerPage;
use App\Domain\Gitlab\Common\SyncDateAfter;
use App\Domain\Gitlab\MergeRequest\Repository\GitlabDataBaseMergeRequestRepositoryInterface;
use App\Domain\Gitlab\ResourceLabelEvent\Repository\GitlabApiResourceLabelEventRepositoryInterface;
use App\Domain\Gitlab\ResourceLabelEvent\Repository\GitlabDataBaseResourceLabelEventRepositoryInterface;

final readonly class SyncGitlabMergeRequestLabelEventsUseCase implements UseCaseInterface
{
    public function __construct(
        private SyncDateAfter $syncDateAfter,
        private GitlabApiResourceLabelEventRepositoryInterface $apiResourceLabelEventRepository,
        private GitlabDataBaseResourceLabelEventRepositoryInterface $databaseResourceLabelEventRepository,
        private GitlabDataBaseMergeRequestRepositoryInterface $dataBaseMergeRequestRepository,
        private SyncOutputInterface $output,
        private ItemsPerPage $itemsPerPage,
    ) {
    }

    public function execute(): void
    {
        $this->output->writeLine('Load label events that created after ' . $this->syncDateAfter->getValueInMainFormat());
        $mergeRequestCollection = $this->dataBaseMergeRequestRepository->getUpdatedAfter($this->syncDateAfter);
        foreach ($mergeRequestCollection as $mergeRequest) {
            $page = 0;
            $projectId = $mergeRequest->projectId->value;
            $mergeRequestIid = $mergeRequest->iid->value;
            $this->output->write(' - # project_id: ' . $projectId . ' merge_request_iid: ' . $mergeRequestIid);
            do {
                ++$page;

                $resourceLabelEventCollection = $this->apiResourceLabelEventRepository->getMergeRequestLabelEvents(
                    $projectId,
                    $mergeRequestIid,
                    [
                        'page' => $page,
                        'per_page' => $this->itemsPerPage->value,
                        'created_after' => $this->syncDateAfter->getValueInMainFormat(),
                    ],
                );

                foreach ($resourceLabelEventCollection as $resourceLabelEvent) {
                    $this->databaseResourceLabelEventRepository->save($resourceLabelEvent);
                }
                $this->output->write(' .');
            } while ($this->itemsPerPage->value === count($resourceLabelEventCollection));
            $this->output->writeLine(' done');
        }
    }
}
