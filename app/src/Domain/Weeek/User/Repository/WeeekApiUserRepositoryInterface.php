<?php

declare(strict_types=1);

namespace App\Domain\Weeek\User\Repository;

use App\Domain\Weeek\User\UserCollection;

interface WeeekApiUserRepositoryInterface
{
    public function get(): UserCollection;
}
