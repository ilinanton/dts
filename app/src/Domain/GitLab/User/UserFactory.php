<?php

namespace App\Domain\GitLab\User;

use App\Domain\GitLab\User\ValueObject\UserAvatarUrl;
use App\Domain\GitLab\User\ValueObject\UserId;
use App\Domain\GitLab\User\ValueObject\UserName;
use App\Domain\GitLab\User\ValueObject\UserUsername;
use App\Domain\GitLab\User\ValueObject\UserWebUrl;

final class UserFactory
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
