<?php

namespace App\Domain\GitLab\Member;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

final class MemberCollection implements IteratorAggregate, Countable
{
    /** @var Member[] */
    private array $list;

    public function add(Member $member): void
    {
        $this->list[] = $member;
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
