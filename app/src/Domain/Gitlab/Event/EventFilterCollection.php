<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\Event;

use App\Domain\Common\AbstractCollection;

/** @extends AbstractCollection<EventFilter> */
final class EventFilterCollection extends AbstractCollection
{
    protected function getType(): string
    {
        return EventFilter::class;
    }
}
