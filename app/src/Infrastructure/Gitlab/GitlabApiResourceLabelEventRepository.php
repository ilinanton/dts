<?php

declare(strict_types=1);

namespace App\Infrastructure\Gitlab;

use App\Domain\Gitlab\Source\GitlabSourceResourceLabelEventInterface;
use App\Domain\Gitlab\ResourceLabelEvent\Repository\GitlabSourceResourceLabelEventRepositoryInterface;
use App\Domain\Gitlab\ResourceLabelEvent\ResourceLabelEventCollection;
use App\Domain\Gitlab\ResourceLabelEvent\ResourceLabelEventFactory;

final readonly class GitlabApiResourceLabelEventRepository implements GitlabSourceResourceLabelEventRepositoryInterface
{
    public function __construct(
        private GitlabSourceResourceLabelEventInterface $client,
    ) {
    }

    public function getMergeRequestLabelEvents(
        int $projectId,
        int $mergeRequestIid,
        array $params = [],
    ): ResourceLabelEventCollection {
        $data = $this->client->getMergeRequestLabelEvents($projectId, $mergeRequestIid, $params);

        $collection = new ResourceLabelEventCollection();
        $factory = new ResourceLabelEventFactory();
        foreach ($data as $item) {
            if (is_null($item['label'])) {
                continue;
            }
            $item['label_id'] = $item['label']['id'];
            $item['project_id'] = $projectId;
            $collection->add($factory->create($item));
        }

        return $collection;
    }
}
