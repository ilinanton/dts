<?php

namespace App\Domain\GitLab\User;

use App\Domain\GitLab\Common\AbstractEntity;
use App\Domain\GitLab\User\ValueObject\UserAvatarUrl;
use App\Domain\GitLab\User\ValueObject\UserId;
use App\Domain\GitLab\User\ValueObject\UserName;
use App\Domain\GitLab\User\ValueObject\UserUsername;
use App\Domain\GitLab\User\ValueObject\UserWebUrl;

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
