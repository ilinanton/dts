<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\ResourceLabelEvent;

use App\Domain\Gitlab\ResourceLabelEvent\ValueObject\ResourceLabelEventActionName;
use App\Domain\Gitlab\ResourceLabelEvent\ValueObject\ResourceLabelEventCreatedAt;
use App\Domain\Gitlab\ResourceLabelEvent\ValueObject\ResourceLabelEventId;
use App\Domain\Gitlab\ResourceLabelEvent\ValueObject\ResourceLabelEventLabelId;
use App\Domain\Gitlab\ResourceLabelEvent\ValueObject\ResourceLabelEventProjectId;
use App\Domain\Gitlab\ResourceLabelEvent\ValueObject\ResourceLabelEventResourceId;
use App\Domain\Gitlab\ResourceLabelEvent\ValueObject\ResourceLabelEventResourceType;
use App\Domain\Gitlab\ResourceLabelEvent\ValueObject\ResourceLabelEventUserId;

final readonly class ResourceLabelEvent
{
    public function __construct(
        public ResourceLabelEventId $id,
        public ResourceLabelEventUserId $userId,
        public ResourceLabelEventCreatedAt $createdAt,
        public ResourceLabelEventResourceType $resourceType,
        public ResourceLabelEventResourceId $resourceId,
        public ResourceLabelEventLabelId $labelId,
        public ResourceLabelEventActionName $actionName,
        public ResourceLabelEventProjectId $projectId,
    ) {
    }
}
