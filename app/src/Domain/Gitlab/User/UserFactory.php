<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\User;

use App\Domain\Gitlab\User\ValueObject\UserAvatarUrlRequired;
use App\Domain\Gitlab\User\ValueObject\UserId;
use App\Domain\Gitlab\User\ValueObject\UserName;
use App\Domain\Gitlab\User\ValueObject\UserUsername;
use App\Domain\Gitlab\User\ValueObject\UserRequiredWebUrl;

final readonly class UserFactory
{
    public function create(array $data): User
    {
        return new User(
            new UserId($data['id'] ?? 0),
            new UserUsername($data['username'] ?? ''),
            new UserName($data['name'] ?? ''),
            new UserAvatarUrlRequired($data['avatar_url'] ?? ''),
            new UserRequiredWebUrl($data['web_url'] ?? '')
        );
    }
}
