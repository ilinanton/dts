<?php

declare(strict_types=1);

namespace App\Application\Gitlab;

use App\Application\Common\Paginator;
use App\Application\SyncOutputInterface;
use App\Application\UseCaseInterface;
use App\Domain\Gitlab\Common\SyncDateAfter;
use App\Domain\Gitlab\MergeRequest\Repository\GitlabDataBaseMergeRequestRepositoryInterface;
use App\Domain\Gitlab\MergeRequest\ValueObject\UpdatedAfterDate;
use App\Domain\Gitlab\ResourceLabelEvent\Repository\GitlabApiResourceLabelEventRepositoryInterface;
use App\Domain\Gitlab\ResourceLabelEvent\Repository\GitlabDataBaseResourceLabelEventRepositoryInterface;
use App\Domain\Gitlab\ResourceLabelEvent\ResourceLabelEventCollection;

final readonly class SyncGitlabMergeRequestLabelEventsUseCase implements UseCaseInterface
{
    public function __construct(
        private SyncDateAfter $syncDateAfter,
        private GitlabApiResourceLabelEventRepositoryInterface $apiResourceLabelEventRepository,
        private GitlabDataBaseResourceLabelEventRepositoryInterface $databaseResourceLabelEventRepository,
        private GitlabDataBaseMergeRequestRepositoryInterface $dataBaseMergeRequestRepository,
        private SyncOutputInterface $output,
        private Paginator $paginator,
    ) {
    }

    public function execute(): void
    {
        $this->output->writeLine('Load label events that created after ' . $this->syncDateAfter->getValueInMainFormat());
        $updatedAfterDate = new UpdatedAfterDate($this->syncDateAfter->getValueInMainFormat());
        $mergeRequestCollection = $this->dataBaseMergeRequestRepository->getUpdatedAfter($updatedAfterDate);
        foreach ($mergeRequestCollection as $mergeRequest) {
            $projectId = $mergeRequest->projectId->value;
            $mergeRequestIid = $mergeRequest->iid->value;
            $this->output->write(' - # project_id: ' . $projectId . ' merge_request_iid: ' . $mergeRequestIid);

            $baseParams = ['created_after' => $this->syncDateAfter->getValueInMainFormat()];

            $items = $this->paginator->paginate(
                function (array $params) use ($projectId, $mergeRequestIid): ResourceLabelEventCollection {
                    $this->output->write(' .');
                    return $this->apiResourceLabelEventRepository->getMergeRequestLabelEvents(
                        $projectId,
                        $mergeRequestIid,
                        $params,
                    );
                },
                $baseParams,
            );

            foreach ($items as $resourceLabelEvent) {
                $this->databaseResourceLabelEventRepository->save($resourceLabelEvent);
            }
            $this->output->writeLine(' done');
        }
    }
}
