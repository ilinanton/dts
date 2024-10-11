<?php

namespace App\Domain\Gitlab\Event;

use App\Domain\Gitlab\Event\ValueObject\EventActionName;
use App\Domain\Gitlab\Event\ValueObject\EventAuthorId;
use App\Domain\Gitlab\Event\ValueObject\EventCreatedAt;
use App\Domain\Gitlab\Event\ValueObject\EventId;
use App\Domain\Gitlab\Event\ValueObject\EventNote;
use App\Domain\Gitlab\Event\ValueObject\EventProjectId;
use App\Domain\Gitlab\Event\ValueObject\EventPushData;
use App\Domain\Gitlab\Event\ValueObject\EventTargetId;
use App\Domain\Gitlab\Event\ValueObject\EventTargetIid;
use App\Domain\Gitlab\Event\ValueObject\EventTargetTitle;
use App\Domain\Gitlab\Event\ValueObject\EventTargetType;

final readonly class Event
{
    public function __construct(
        public EventId $id,
        public EventProjectId $projectId,
        public EventActionName $actionName,
        public EventTargetId $targetId,
        public EventTargetIid $targetIid,
        public EventTargetType $targetType,
        public EventAuthorId $authorId,
        public EventTargetTitle $targetTitle,
        public EventCreatedAt $createdAt,
        public EventPushData $pushData,
        public EventNote $note,
    ) {
    }
}
