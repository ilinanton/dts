<?php

declare(strict_types=1);

namespace App\Domain\Weeek\User\Factory;

use App\Domain\Weeek\User\User;
use App\Domain\Weeek\User\ValueObject\UserEmail;
use App\Domain\Weeek\User\ValueObject\UserId;
use App\Domain\Weeek\User\ValueObject\UserLogo;

final readonly class UserFromArray
{
    public function __construct(
        private array $data,
    ) {
    }

    public function create(): User
    {
        return new User(
            new UserId($this->data['id'] ?? ''),
            new UserEmail($this->data['email'] ?? ''),
            new UserLogo($this->data['logo'] ?? ''),
        );
    }
}
