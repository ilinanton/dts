<?php

declare(strict_types=1);

namespace App\Domain\Weeek\Tag\Factory;

use App\Domain\Weeek\Tag\TagCollection;

final class TagCollectionFromArray
{
    public function __construct(
        private array $data,
    ) {
    }

    public function create(): TagCollection
    {
        $collection = new TagCollection();
        array_walk(
            $this->data,
            function (array $item) use ($collection): void {
                $factory = new TagFromArray($item);
                $collection->add($factory->create());
            },
        );

        return $collection;
    }
}
