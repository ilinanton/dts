<?php

namespace App\Infrastructure\GitLab;

use App\Domain\GitLab\Member\Member;
use App\Domain\GitLab\Member\MemberCollection;
use App\Domain\GitLab\Member\MemberFactory;
use App\Domain\GitLab\Member\Repository\GitLabDataBaseMemberRepositoryInterface;
use PDO;

final class GitLabMySqlMemberRepository implements GitLabDataBaseMemberRepositoryInterface
{
    private PDO $pdo;
    private MemberFactory $memberFactory;

    public function __construct(PDO $pdo, MemberFactory $memberFactory)
    {
        $this->pdo = $pdo;
        $this->memberFactory = $memberFactory;
    }

    public function save(Member $object): void
    {
        $sql = <<<SQL
INSERT INTO git_lab_member (id, username, name, avatar_url, web_url)
VALUES (:ID, :USERNAME, :NAME, :AVATAR_URL, :WEB_URL)
ON DUPLICATE KEY UPDATE username = :USERNAME, name = :NAME, avatar_url = :AVATAR_URL, web_url = :WEB_URL
SQL;

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':ID' => $object->getId()->getValue(),
            ':USERNAME' => $object->getUsername()->getValue(),
            ':NAME' => $object->getName()->getValue(),
            ':AVATAR_URL' => $object->getAvatarUrl()->getValue(),
            ':WEB_URL' => $object->getWebUrl()->getValue(),
        ]);
    }

    public function getAll(): MemberCollection
    {
        $sql = <<<SQL
SELECT id, username, name, avatar_url, web_url
FROM git_lab_member
SQL;

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll();
        $memberCollection = new MemberCollection();

        foreach ($data as $item) {
            $project = $this->memberFactory->create($item);
            $memberCollection->add($project);
        }

        return $memberCollection;
    }
}
