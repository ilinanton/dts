<?php

namespace App\Domain\GitLab\Event;

use App\Domain\GitLab\Event\ValueObject\EventActionName;
use App\Domain\GitLab\Event\ValueObject\EventAuthorId;
use App\Domain\GitLab\Event\ValueObject\EventCreateAt;
use App\Domain\GitLab\Event\ValueObject\EventId;
use App\Domain\GitLab\Event\ValueObject\EventNote;
use App\Domain\GitLab\Event\ValueObject\EventProjectId;
use App\Domain\GitLab\Event\ValueObject\EventPushData;
use App\Domain\GitLab\Event\ValueObject\EventTargetId;
use App\Domain\GitLab\Event\ValueObject\EventTargetIid;
use App\Domain\GitLab\Event\ValueObject\EventTargetTitle;
use App\Domain\GitLab\Event\ValueObject\EventTargetType;

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
        private EventCreateAt $createdAt,
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

    public function getCreatedAt(): EventCreateAt
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
