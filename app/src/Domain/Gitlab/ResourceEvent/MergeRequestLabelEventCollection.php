<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\ResourceEvent;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

final class MergeRequestLabelEventCollection implements IteratorAggregate, Countable
{
    /** @var MergeRequestLabelEvent[] */
    private array $list = [];

    public function add(MergeRequestLabelEvent $item): void
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
