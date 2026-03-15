<?php

declare(strict_types=1);

namespace App\Infrastructure\Gitlab\Factory;

use App\Domain\Gitlab\Event\Event;
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

final readonly class EventFactory
{
    public function __construct(
        private PushDataFromArray $pushDataFactory,
        private NoteFactory $noteFactory,
    ) {
    }

    /**
     * @param array{
     *     id: int,
     *     project_id: int,
     *     action_name: string,
     *     target_id?: int,
     *     target_iid?: int,
     *     target_type?: string,
     *     author_id: int,
     *     target_title?: string,
     *     created_at: string,
     *     push_data?: array<string, mixed>,
     *     note?: array<string, mixed>,
     * } $data
     */
    public function create(array $data): Event
    {
        return new Event(
            new EventId($data['id']),
            new EventProjectId($data['project_id']),
            new EventActionName($data['action_name']),
            new EventTargetId($data['target_id'] ?? 0),
            new EventTargetIid($data['target_iid'] ?? 0),
            new EventTargetType($data['target_type'] ?? ''),
            new EventAuthorId($data['author_id']),
            new EventTargetTitle($data['target_title'] ?? ''),
            new EventCreatedAt($data['created_at']),
            new EventPushData($this->pushDataFactory->create($data['push_data'] ?? [])),
            new EventNote($this->noteFactory->create($data['note'] ?? [])),
        );
    }
}
