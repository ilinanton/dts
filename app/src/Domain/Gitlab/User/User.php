<?php

namespace App\Domain\Gitlab\User;

use App\Domain\Gitlab\Common\AbstractEntity;
use App\Domain\Gitlab\User\ValueObject\UserAvatarUrl;
use App\Domain\Gitlab\User\ValueObject\UserId;
use App\Domain\Gitlab\User\ValueObject\UserName;
use App\Domain\Gitlab\User\ValueObject\UserUsername;
use App\Domain\Gitlab\User\ValueObject\UserWebUrl;

final readonly class User extends AbstractEntity
{
    public function __construct(
        private UserId $id,
        private UserUsername $username,
        private UserName $name,
        private UserAvatarUrl $avatarUrl,
        private UserWebUrl $webUrl,
    ) {
    }

    public function getId(): UserId
    {
        return $this->id;
    }

    public function getUsername(): UserUsername
    {
        return $this->username;
    }

    public function getName(): UserName
    {
        return $this->name;
    }

    public function getAvatarUrl(): UserAvatarUrl
    {
        return $this->avatarUrl;
    }

    public function getWebUrl(): UserWebUrl
    {
        return $this->webUrl;
    }
}
