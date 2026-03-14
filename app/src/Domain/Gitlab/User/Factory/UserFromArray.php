<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\User\Factory;

use App\Domain\Gitlab\User\User;
use App\Domain\Gitlab\User\ValueObject\UserAvatarUrlRequired;
use App\Domain\Gitlab\User\ValueObject\UserId;
use App\Domain\Gitlab\User\ValueObject\UserName;
use App\Domain\Gitlab\User\ValueObject\UserRequiredWebUrl;
use App\Domain\Gitlab\User\ValueObject\UserState;
use App\Domain\Gitlab\User\ValueObject\UserUsername;

final readonly class UserFromArray
{
    public function create(array $data): User
    {
        return new User(
            new UserId($data['id'] ?? 0),
            new UserUsername($data['username'] ?? ''),
            new UserName($data['name'] ?? ''),
            new UserAvatarUrlRequired($data['avatar_url'] ?? ''),
            new UserRequiredWebUrl($data['web_url'] ?? ''),
            UserState::from($data['state'] ?? 'active')
        );
    }
}
