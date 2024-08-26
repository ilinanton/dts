<?php

namespace App\Domain\Gitlab\User;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

final class UserCollection implements IteratorAggregate, Countable
{
    /** @var User[] */
    private array $list = [];

    public function add(User $item): void
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
