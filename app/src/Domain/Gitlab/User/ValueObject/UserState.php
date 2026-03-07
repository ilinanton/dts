<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\User\ValueObject;

enum UserState: string
{
    case Active = 'active';
    case Blocked = 'blocked';
    case Deactivated = 'deactivated';
}
