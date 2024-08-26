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
        private EventId $id,
        private EventProjectId $projectId,
        private EventActionName $actionName,
        private EventTargetId $targetId,
        private EventTargetIid $targetIid,
        private EventTargetType $targetType,
        private EventAuthorId $authorId,
        private EventTargetTitle $targetTitle,
        private EventCreatedAt $createdAt,
        private EventPushData $pushData,
        private EventNote $note,
    ) {
    }

    public function getId(): EventId
    {
        return $this->id;
    }

    public function getProjectId(): EventProjectId
    {
        return $this->projectId;
    }

    public function getActionName(): EventActionName
    {
        return $this->actionName;
    }

    public function getTargetId(): EventTargetId
    {
        return $this->targetId;
    }

    public function getTargetIid(): EventTargetIid
    {
        return $this->targetIid;
    }

    public function getTargetType(): EventTargetType
    {
        return $this->targetType;
    }

    public function getAuthorId(): EventAuthorId
    {
        return $this->authorId;
    }

    public function getTargetTitle(): EventTargetTitle
    {
        return $this->targetTitle;
    }

    public function getCreatedAt(): EventCreatedAt
    {
        return $this->createdAt;
    }

    public function getPushData(): EventPushData
    {
        return $this->pushData;
    }

    public function getNote(): EventNote
    {
        return $this->note;
    }
}
