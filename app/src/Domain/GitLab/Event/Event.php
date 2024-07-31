<?php

namespace App\Domain\GitLab\Event;

use App\Domain\GitLab\Event\ValueObject\EventActionName;
use App\Domain\GitLab\Event\ValueObject\EventAuthorId;
use App\Domain\GitLab\Event\ValueObject\EventCreateAt;
use App\Domain\GitLab\Event\ValueObject\EventId;
use App\Domain\GitLab\Event\ValueObject\EventProjectId;
use App\Domain\GitLab\Event\ValueObject\EventTargetId;
use App\Domain\GitLab\Event\ValueObject\EventTargetIid;
use App\Domain\GitLab\Event\ValueObject\EventTargetTitle;
use App\Domain\GitLab\Event\ValueObject\EventTargetType;

final class Event
{
    private EventId $id;
    private EventProjectId $projectId;
    private EventActionName $actionName;
    private EventTargetId $targetId;
    private EventTargetIid $targetIid;
    private EventTargetType $targetType;
    private EventAuthorId $authorId;
    private EventTargetTitle $targetTitle;
    private EventCreateAt $createdAt;

    public function __construct(
        EventId $id,
        EventProjectId $projectId,
        EventActionName $actionName,
        EventTargetId $targetId,
        EventTargetIid $targetIid,
        EventAuthorId $authorId,
        EventTargetTitle $targetTitle,
        EventCreateAt $createdAt
    ) {
        $this->id = $id;
        $this->projectId = $projectId;
        $this->actionName = $actionName;
        $this->targetId = $targetId;
        $this->targetIid = $targetIid;
        $this->authorId = $authorId;
        $this->targetTitle = $targetTitle;
        $this->createdAt = $createdAt;
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
}
