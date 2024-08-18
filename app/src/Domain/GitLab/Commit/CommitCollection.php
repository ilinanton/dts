<?php

namespace App\Domain\GitLab\Commit;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

final class CommitCollection implements IteratorAggregate, Countable
{
    /** @var Commit[] */
    private array $list = [];

    public function add(Commit $item): void
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
