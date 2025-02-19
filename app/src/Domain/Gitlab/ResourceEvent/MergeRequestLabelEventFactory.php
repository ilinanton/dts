<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\ResourceEvent;

use App\Domain\Gitlab\ResourceEvent\ValueObject\ResourceEventActionName;
use App\Domain\Gitlab\ResourceEvent\ValueObject\ResourceEventCreatedAt;
use App\Domain\Gitlab\ResourceEvent\ValueObject\ResourceEventId;
use App\Domain\Gitlab\ResourceEvent\ValueObject\ResourceEventLabelId;
use App\Domain\Gitlab\ResourceEvent\ValueObject\ResourceEventMergeRequestId;
use App\Domain\Gitlab\ResourceEvent\ValueObject\ResourceEventUserId;

final readonly class MergeRequestLabelEventFactory
{
    public function create(array $data): MergeRequestLabelEvent
    {
        return new MergeRequestLabelEvent(
            new ResourceEventId($data['id']),
            new ResourceEventUserId($data['user']['id']),
            new ResourceEventCreatedAt($data['created_at']),
            new ResourceEventMergeRequestId($data['resource_id']),
            new ResourceEventLabelId($data['label']['id']),
            new ResourceEventActionName($data['action']),
        );
    }
}
