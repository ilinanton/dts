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
        $userCollection = new UserCollection();
        array_walk(
            $this->data,
            function (array $item) use ($userCollection): void {
                $userFactory = new UserFromArray($item);
                $userCollection->add($userFactory->create());
            },
        );

        return $userCollection;
    }
}
