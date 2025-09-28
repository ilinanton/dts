<?php

declare(strict_types=1);

namespace App\Domain\Weeek\Tag;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

final class TagCollection implements IteratorAggregate, Countable
{
    /** @var Tag[] */
    private array $list = [];

    public function add(Tag $item): void
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
