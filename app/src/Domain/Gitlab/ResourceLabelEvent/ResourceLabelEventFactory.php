<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\ResourceLabelEvent;

use App\Domain\Gitlab\ResourceLabelEvent\ValueObject\ResourceLabelEventActionName;
use App\Domain\Gitlab\ResourceLabelEvent\ValueObject\ResourceLabelEventCreatedAt;
use App\Domain\Gitlab\ResourceLabelEvent\ValueObject\ResourceLabelEventId;
use App\Domain\Gitlab\ResourceLabelEvent\ValueObject\ResourceLabelEventLabelId;
use App\Domain\Gitlab\ResourceLabelEvent\ValueObject\ResourceLabelEventResourceId;
use App\Domain\Gitlab\ResourceLabelEvent\ValueObject\ResourceLabelEventResourceType;
use App\Domain\Gitlab\ResourceLabelEvent\ValueObject\ResourceLabelEventUserId;

final readonly class ResourceLabelEventFactory
{
    public function create(array $data): ResourceLabelEvent
    {
        return new ResourceLabelEvent(
            new ResourceLabelEventId($data['id']),
            new ResourceLabelEventUserId($data['user']['id']),
            new ResourceLabelEventCreatedAt($data['created_at']),
            new ResourceLabelEventResourceType($data['resource_type']),
            new ResourceLabelEventResourceId($data['resource_id']),
            new ResourceLabelEventLabelId($data['label']['id']),
            new ResourceLabelEventActionName($data['action']),
        );
    }
}
