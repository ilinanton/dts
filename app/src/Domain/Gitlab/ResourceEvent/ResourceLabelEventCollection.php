<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\ResourceEvent;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

final class ResourceLabelEventCollection implements IteratorAggregate, Countable
{
    /** @var ResourceLabelEvent[] */
    private array $list = [];

    public function add(ResourceLabelEvent $item): void
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
