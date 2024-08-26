<?php

namespace App\Domain\Gitlab\User;

use App\Domain\Gitlab\User\ValueObject\UserAvatarUrl;
use App\Domain\Gitlab\User\ValueObject\UserId;
use App\Domain\Gitlab\User\ValueObject\UserName;
use App\Domain\Gitlab\User\ValueObject\UserUsername;
use App\Domain\Gitlab\User\ValueObject\UserWebUrl;

final readonly class UserFactory
{
    public function create(array $data): User
    {
        return new User(
            new UserId($data['id'] ?? 0),
            new UserUsername($data['username'] ?? ''),
            new UserName($data['name'] ?? ''),
            new UserAvatarUrl($data['avatar_url'] ?? ''),
            new UserWebUrl($data['web_url'] ?? '')
        );
    }
}
