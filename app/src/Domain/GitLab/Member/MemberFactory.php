<?php

namespace App\Domain\GitLab\Member;

use App\Domain\GitLab\Member\ValueObject\MemberAvatarUrl;
use App\Domain\GitLab\Member\ValueObject\MemberId;
use App\Domain\GitLab\Member\ValueObject\MemberName;
use App\Domain\GitLab\Member\ValueObject\MemberUsername;
use App\Domain\GitLab\Member\ValueObject\MemberWebUrl;

final class MemberFactory
{
    public function create(array $data): Member
    {
        return new Member(
            new MemberId($data['id'] ?? 0),
            new MemberUsername($data['username'] ?? ''),
            new MemberName($data['name'] ?? ''),
            new MemberAvatarUrl($data['avatar_url'] ?? ''),
            new MemberWebUrl($data['web_url'] ?? '')
        );
    }
}
