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

final readonly class ResourceLabelEventFactory
{
    /**
     * @param array{
     *     id: int,
     *     user: array{id: int},
     *     created_at: string,
     *     resource_type: string,
     *     resource_id: int,
     *     label_id: int,
     *     action: string,
     *     project_id: int,
     * } $data
     */
    public function create(array $data): ResourceLabelEvent
    {
        return new ResourceLabelEvent(
            new ResourceLabelEventId($data['id']),
            new ResourceLabelEventUserId($data['user']['id']),
            new ResourceLabelEventCreatedAt($data['created_at']),
            new ResourceLabelEventResourceType($data['resource_type']),
            new ResourceLabelEventResourceId($data['resource_id']),
            new ResourceLabelEventLabelId($data['label_id']),
            new ResourceLabelEventActionName($data['action']),
            new ResourceLabelEventProjectId($data['project_id']),
        );
    }
}
