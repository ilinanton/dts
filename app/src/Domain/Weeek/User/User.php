<?php

declare(strict_types=1);

namespace App\Domain\Weeek\User;

use App\Domain\Weeek\User\ValueObject\UserEmail;
use App\Domain\Weeek\User\ValueObject\UserId;
use App\Domain\Weeek\User\ValueObject\UserLogo;

final readonly class User
{
    public function __construct(
        public UserId $id,
        public UserEmail $email,
        public UserLogo $logo,
    ) {
    }
}
