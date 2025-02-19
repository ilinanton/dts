<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\Label;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

final class LabelCollection implements IteratorAggregate, Countable
{
    /** @var Label[] */
    private array $list = [];

    public function add(Label $item): void
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
