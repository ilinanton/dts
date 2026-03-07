<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\Event;

use App\Domain\Common\AbstractCollection;

/** @extends AbstractCollection<Event> */
final class EventCollection extends AbstractCollection
{
    protected function getType(): string
    {
        return Event::class;
    }
}
