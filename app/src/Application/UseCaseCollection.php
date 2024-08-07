<?php

namespace App\Application;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

final readonly class UseCaseCollection implements IteratorAggregate, Countable
{
    /** @var UseCaseInterface[] */
    private array $list = [];

    public function add(UseCaseInterface $item): void
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
