<?php

namespace App\Domain\GitLab\Project;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

final class ProjectCollection implements IteratorAggregate, Countable
{
    /** @var Project[] */
    private array $list = [];

    public function add(Project $item): void
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
