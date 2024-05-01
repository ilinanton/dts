<?php

namespace App\Domain\GitLab\Project;

use ArrayIterator;
use IteratorAggregate;
use Traversable;

final class ProjectCollection implements IteratorAggregate
{
    /** @var Project[] */
    private array $list;

    public function add(Project $project): void
    {
        $this->list[] = $project;
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->list);
    }
}
