<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\User;

use App\Domain\Common\AbstractEntity;
use App\Domain\Gitlab\User\ValueObject\UserAvatarUrlRequired;
use App\Domain\Gitlab\User\ValueObject\UserId;
use App\Domain\Gitlab\User\ValueObject\UserName;
use App\Domain\Gitlab\User\ValueObject\UserUsername;
use App\Domain\Gitlab\User\ValueObject\UserRequiredWebUrl;

final readonly class User extends AbstractEntity
{
    public function __construct(
        public UserId $id,
        public UserUsername $username,
        public UserName $name,
        public UserAvatarUrlRequired $avatarUrl,
        public UserRequiredWebUrl $webUrl,
    ) {
    }
}
