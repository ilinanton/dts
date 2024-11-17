<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\User\Factory;

use App\Domain\Gitlab\User\User;
use App\Domain\Gitlab\User\ValueObject\UserAvatarUrlRequired;
use App\Domain\Gitlab\User\ValueObject\UserId;
use App\Domain\Gitlab\User\ValueObject\UserName;
use App\Domain\Gitlab\User\ValueObject\UserRequiredWebUrl;
use App\Domain\Gitlab\User\ValueObject\UserUsername;

final readonly class UserFromArray
{
    public function __construct(
        private array $data,
    ) {
    }

    public function create(): User
    {
        return new User(
            new UserId($this->data['id'] ?? 0),
            new UserUsername($this->data['username'] ?? ''),
            new UserName($this->data['name'] ?? ''),
            new UserAvatarUrlRequired($this->data['avatar_url'] ?? ''),
            new UserRequiredWebUrl($this->data['web_url'] ?? '')
        );
    }
}
