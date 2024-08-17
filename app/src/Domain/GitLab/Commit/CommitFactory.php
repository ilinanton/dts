<?php

namespace App\Domain\GitLab\Commit;

use App\Domain\GitLab\Commit\ValueObject\CommitAuthoredDate;
use App\Domain\GitLab\Commit\ValueObject\CommitAuthorEmail;
use App\Domain\GitLab\Commit\ValueObject\CommitAuthorName;
use App\Domain\GitLab\Commit\ValueObject\CommitCommittedDate;
use App\Domain\GitLab\Commit\ValueObject\CommitCommitterEmail;
use App\Domain\GitLab\Commit\ValueObject\CommitCommitterName;
use App\Domain\GitLab\Commit\ValueObject\CommitCreatedAt;
use App\Domain\GitLab\Commit\ValueObject\CommitId;
use App\Domain\GitLab\Commit\ValueObject\CommitMessage;
use App\Domain\GitLab\Commit\ValueObject\CommitShortId;
use App\Domain\GitLab\Commit\ValueObject\CommitStats;
use App\Domain\GitLab\Commit\ValueObject\CommitTitle;
use App\Domain\GitLab\Commit\ValueObject\CommitWebUrl;

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
