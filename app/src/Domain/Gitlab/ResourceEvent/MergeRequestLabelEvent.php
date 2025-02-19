<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\ResourceEvent;

use App\Domain\Gitlab\ResourceEvent\ValueObject\ResourceEventActionName;
use App\Domain\Gitlab\ResourceEvent\ValueObject\ResourceEventCreatedAt;
use App\Domain\Gitlab\ResourceEvent\ValueObject\ResourceEventId;
use App\Domain\Gitlab\ResourceEvent\ValueObject\ResourceEventLabelId;
use App\Domain\Gitlab\ResourceEvent\ValueObject\ResourceEventMergeRequestId;
use App\Domain\Gitlab\ResourceEvent\ValueObject\ResourceEventUserId;

final readonly class MergeRequestLabelEvent
{
    public function __construct(
        public ResourceEventId $id,
        public ResourceEventUserId $userId,
        public ResourceEventCreatedAt $createdAt,
        public ResourceEventMergeRequestId $mergeRequestId,
        public ResourceEventLabelId $labelId,
        public ResourceEventActionName $actionName,
    ) {
    }
}