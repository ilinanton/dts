<?php

namespace App\Domain\GitLab\Member;

use App\Domain\GitLab\Member\ValueObject\MemberAvatarUrl;
use App\Domain\GitLab\Member\ValueObject\MemberId;
use App\Domain\GitLab\Member\ValueObject\MemberName;
use App\Domain\GitLab\Member\ValueObject\MemberUsername;
use App\Domain\GitLab\Member\ValueObject\MemberWebUrl;

final class Member
{
    private MemberId $id;
    private MemberUsername $username;
    private MemberName $name;
    private MemberAvatarUrl $avatarUrl;
    private MemberWebUrl $webUrl;

    public function __construct(
        MemberId $id,
        MemberUsername $username,
        MemberName $name,
        MemberAvatarUrl $avatarUrl,
        MemberWebUrl $webUrl
    ) {
        $this->id = $id;
        $this->username = $username;
        $this->name = $name;
        $this->avatarUrl = $avatarUrl;
        $this->webUrl = $webUrl;
    }

    public function getId(): MemberId
    {
        return $this->id;
    }

    public function getUsername(): MemberUsername
    {
        return $this->username;
    }

    public function getName(): MemberName
    {
        return $this->name;
    }

    public function getAvatarUrl(): MemberAvatarUrl
    {
        return $this->avatarUrl;
    }

    public function getWebUrl(): MemberWebUrl
    {
        return $this->webUrl;
    }
}
