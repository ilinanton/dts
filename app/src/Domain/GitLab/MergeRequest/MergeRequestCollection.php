<?php

namespace App\Domain\GitLab\MergeRequest;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

final class MergeRequestCollection implements IteratorAggregate, Countable
{
    /** @var MergeRequest[] */
    private array $list;

    public function add(MergeRequest $item): void
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
