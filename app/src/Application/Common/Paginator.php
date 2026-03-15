<?php

declare(strict_types=1);

namespace App\Application\Common;

use InvalidArgumentException;

final readonly class Paginator
{
    public function __construct(
        private int $itemsPerPage,
    ) {
        if ($itemsPerPage <= 0) {
            throw new InvalidArgumentException('Items per page must be greater than zero');
        }
    }

    /**
     * @template TItem
     * @template TCollection of (\Countable&iterable<TItem>)
     * @param callable(array<string, mixed>): TCollection $fetch
     * @param array<string, mixed> $baseParams
     * @return iterable<TItem>
     */
    public function paginate(callable $fetch, array $baseParams = []): iterable
    {
        $page = 0;
        do {
            ++$page;
            $params = array_merge($baseParams, [
                'page' => $page,
                'per_page' => $this->itemsPerPage,
            ]);
            $collection = $fetch($params);
            foreach ($collection as $item) {
                yield $item;
            }
        } while ($this->itemsPerPage === count($collection));
    }
}
