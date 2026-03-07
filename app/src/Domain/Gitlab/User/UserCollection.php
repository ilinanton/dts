<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\User;

use App\Domain\Common\AbstractCollection;

/** @extends AbstractCollection<User> */
final class UserCollection extends AbstractCollection
{
    protected function getType(): string
    {
        return User::class;
    }
}
