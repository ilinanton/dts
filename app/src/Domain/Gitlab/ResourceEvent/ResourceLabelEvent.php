<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\ResourceEvent;

use App\Domain\Gitlab\ResourceEvent\ValueObject\ResourceEventActionName;
use App\Domain\Gitlab\ResourceEvent\ValueObject\ResourceEventCreatedAt;
use App\Domain\Gitlab\ResourceEvent\ValueObject\ResourceEventGroupId;
use App\Domain\Gitlab\ResourceEvent\ValueObject\ResourceEventId;
use App\Domain\Gitlab\ResourceEvent\ValueObject\ResourceEventLabelId;
use App\Domain\Gitlab\ResourceEvent\ValueObject\ResourceEventProjectId;
use App\Domain\Gitlab\ResourceEvent\ValueObject\ResourceEventResourceId;
use App\Domain\Gitlab\ResourceEvent\ValueObject\ResourceEventResourceType;
use App\Domain\Gitlab\ResourceEvent\ValueObject\ResourceEventUserId;

final readonly class ResourceLabelEvent
{
    public function __construct(
        public ResourceEventId $id,
        public ResourceEventUserId $userId,
        public ResourceEventCreatedAt $createdAt,
        public ResourceEventResourceType $resourceType,
        public ResourceEventResourceId $resourceId,
        public ResourceEventLabelId $labelId,
        public ResourceEventActionName $actionName,
        public ResourceEventProjectId $projectId,
        public ResourceEventGroupId $groupId,
    ) {
    }
}