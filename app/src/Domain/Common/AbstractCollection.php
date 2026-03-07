<?php

declare(strict_types=1);

namespace App\Domain\Common;

use ArrayIterator;
use Countable;
use InvalidArgumentException;
use IteratorAggregate;
use Traversable;

/**
 * @template T of object
 * @implements IteratorAggregate<int, T>
 */
abstract class AbstractCollection implements IteratorAggregate, Countable
{
    /** @var list<T> */
    private array $list = [];

    /** @return class-string<T> */
    abstract protected function getType(): string;

    public function add(object $item): void
    {
        $type = $this->getType();

        if (!$item instanceof $type) {
            throw new InvalidArgumentException(
                sprintf('Expected %s, got %s', $type, get_class($item)),
            );
        }

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
