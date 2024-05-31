<?php

namespace App\Infrastructure\GitLab;

use App\Domain\GitLab\Member\Member;
use App\Domain\GitLab\Member\Repository\GitLabDataBaseMemberRepositoryInterface;
use PDO;

final class GitLabMySqlMemberRepository implements GitLabDataBaseMemberRepositoryInterface
{
    private PDO $pdo;

    public function __construct(string $dsn, string $userName, string $password)
    {
        $this->pdo = new PDO($dsn, $userName, $password);
    }

    public function save(Member $member): void
    {
        $sql = <<<SQL
INSERT INTO git_lab_member (id, username, name, avatar_url, web_url)
VALUES (:ID, :USERNAME, :NAME, :AVATAR_URL, :WEB_URL)
ON DUPLICATE KEY UPDATE username = :USERNAME, name = :NAME, avatar_url = :AVATAR_URL, web_url = :WEB_URL
SQL;

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':ID' => $member->getId()->getValue(),
            ':USERNAME' => $member->getUsername()->getValue(),
            ':NAME' => $member->getName()->getValue(),
            ':AVATAR_URL' => $member->getAvatarUrl()->getValue(),
            ':WEB_URL' => $member->getWebUrl()->getValue(),
        ]);
    }
}
