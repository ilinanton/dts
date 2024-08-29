<?php

namespace App\Domain\Git\User;

use App\Domain\Git\User\ValueObject\UserEmail;
use App\Domain\Git\User\ValueObject\UserName;

final readonly class User
{
    public function __construct(
        private UserName $name,
        private UserEmail $email,
    ) {
    }

    public function getName(): UserName
    {
        return $this->name;
    }

    public function getEmail(): UserEmail
    {
        return $this->email;
    }
}
