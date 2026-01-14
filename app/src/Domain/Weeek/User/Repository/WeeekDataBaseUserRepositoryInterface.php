<?php

declare(strict_types=1);

namespace App\Domain\Weeek\User\Repository;

use App\Domain\Weeek\User\User;
use App\Domain\Weeek\User\UserCollection;

interface WeeekDataBaseUserRepositoryInterface
{
    public function save(User $object): void;

    public function getAll(): UserCollection;
}
