<?php

declare(strict_types=1);

namespace App\Infrastructure\Gitlab\Factory;

use App\Domain\Gitlab\User\UserCollection;

final readonly class UserCollectionFromArray
{
    public function create(array $data): UserCollection
    {
        $userCollection = new UserCollection();
        $userFactory = new UserFromArray();
        array_walk(
            $data,
            function (array $item) use ($userCollection, $userFactory): void {
                $userCollection->add($userFactory->create($item));
            },
        );

        return $userCollection;
    }
}
