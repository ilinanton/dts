<?php

namespace App\Domain\Gitlab\Commit;

use App\Domain\Gitlab\Commit\ValueObject\CommitAuthoredDate;
use App\Domain\Gitlab\Commit\ValueObject\CommitAuthorEmail;
use App\Domain\Gitlab\Commit\ValueObject\CommitAuthorName;
use App\Domain\Gitlab\Commit\ValueObject\CommitCommittedDate;
use App\Domain\Gitlab\Commit\ValueObject\CommitCommitterEmail;
use App\Domain\Gitlab\Commit\ValueObject\CommitCommitterName;
use App\Domain\Gitlab\Commit\ValueObject\CommitCreatedAt;
use App\Domain\Gitlab\Commit\ValueObject\CommitId;
use App\Domain\Gitlab\Commit\ValueObject\CommitMessage;
use App\Domain\Gitlab\Commit\ValueObject\CommitShortId;
use App\Domain\Gitlab\Commit\ValueObject\CommitStats;
use App\Domain\Gitlab\Commit\ValueObject\CommitTitle;
use App\Domain\Gitlab\Commit\ValueObject\CommitWebUrl;

final readonly class CommitFactory
{
    public function create(array $data): Commit
    {
        return new Commit(
            new CommitId($data['id'] ?? ''),
            new CommitShortId($data['short_id'] ?? ''),
            new CommitTitle($data['title'] ?? ''),
            new CommitCreatedAt($data['created_at'] ?? ''),
            new CommitWebUrl($data['web_url'] ?? ''),
            new CommitAuthorName($data['author_name'] ?? ''),
            new CommitAuthorEmail($data['author_email'] ?? ''),
            new CommitAuthoredDate($data['authored_date'] ?? ''),
            new CommitCommitterName($data['committer_name'] ?? ''),
            new CommitCommitterEmail($data['committer_email'] ?? ''),
            new CommitCommittedDate($data['committed_date'] ?? ''),
            new CommitStats($data['stats'] ?? []),
        );
    }
}
