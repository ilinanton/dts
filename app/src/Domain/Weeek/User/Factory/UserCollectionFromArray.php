<?php

declare(strict_types=1);

namespace App\Domain\Weeek\User\Factory;

use App\Domain\Weeek\User\UserCollection;

final class UserCollectionFromArray
{
    public function __construct(
        private array $data,
    ) {
    }

    public function create(): UserCollection
    {
        $collection = new UserCollection();
        array_walk(
            $this->data,
            function (array $item) use ($collection): void {
                $factory = new UserFromArray($item);
                $collection->add($factory->create());
            },
        );

        return $collection;
    }
}
