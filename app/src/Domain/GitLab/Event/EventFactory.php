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

final class EventFactory
{
    public function create(array $data): Event
    {
        return new Event(
            new EventId($data['id'] ?? 0),
            new EventProjectId($data['project_id'] ?? 0),
            new EventActionName($data['action_name'] ?? ''),
            new EventTargetId($data['target_id'] ?? 0),
            new EventTargetIid($data['target_iid'] ?? 0),
            new EventTargetType($data['target_type'] ?? 0),
            new EventAuthorId($data['author_id'] ?? 0),
            new EventTargetTitle($data['target_title'] ?? ''),
            new EventCreateAt($data['created_at'] ?? ''),
            new EventPushData($data['push_data'] ?? []),
            new EventNote($data['note'] ?? []),
        );
    }
}
