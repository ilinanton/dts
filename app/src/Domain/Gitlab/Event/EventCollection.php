<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\Event;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

final class EventCollection implements IteratorAggregate, Countable
{
    /** @var Event[] */
    private array $list = [];

    public function add(Event $item): void
    {
        $this->list[] = $item;
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->list);
    }

    public function count(): int
    {
        return count($this->list);
    }
}
