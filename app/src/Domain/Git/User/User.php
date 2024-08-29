<?php

namespace App\Domain\Git\User;

use App\Domain\Git\User\ValueObject\UserEmail;
use App\Domain\Git\User\ValueObject\UserId;
use App\Domain\Git\User\ValueObject\UserName;
use App\Domain\Git\User\ValueObject\UserProjectId;

final readonly class User
{
    public function __construct(
        public UserId $id,
        public UserProjectId $projectId,
        public UserName $name,
        public UserEmail $email,
    ) {
    }
}
