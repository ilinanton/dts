<?php

declare(strict_types=1);

namespace App\Domain\Report\Metric;

use App\Domain\Report\Common\MetricInterface;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

class MetricCollection implements IteratorAggregate, Countable
{
    /** @var MetricInterface[] */
    private array $list = [];

    public function add(MetricInterface $item): void
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
