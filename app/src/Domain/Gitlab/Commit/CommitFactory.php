<?php

namespace App\Domain\Gitlab\Commit;

use App\Domain\Gitlab\Commit\ValueObject\CommitAuthoredDate;
use App\Domain\Gitlab\Commit\ValueObject\CommitAuthorEmail;
use App\Domain\Gitlab\Commit\ValueObject\CommitAuthorName;
use App\Domain\Gitlab\Commit\ValueObject\CommitCommittedDate;
use App\Domain\Gitlab\Commit\ValueObject\CommitCommitterEmail;
use App\Domain\Gitlab\Commit\ValueObject\CommitCommitterName;
use App\Domain\Gitlab\Commit\ValueObject\CommitCreatedAt;
use App\Domain\Gitlab\Commit\ValueObject\CommitGitCommitId;
use App\Domain\Gitlab\Commit\ValueObject\CommitId;
use App\Domain\Gitlab\Commit\ValueObject\CommitProjectId;
use App\Domain\Gitlab\Commit\ValueObject\CommitTitle;
use App\Domain\Gitlab\Commit\ValueObject\CommitWebUrl;

final readonly class CommitFactory
{
    public function create(int $projectId, array $data): Commit
    {
        $commitId = new CommitId($data['id'] ?? '');
        return new Commit(
            $commitId,
            new CommitProjectId($projectId),
            new CommitGitCommitId(substr($commitId->getValue(), 0, 32)),
            new CommitTitle($data['title'] ?? ''),
            new CommitCreatedAt($data['created_at'] ?? ''),
            new CommitWebUrl($data['web_url'] ?? ''),
            new CommitAuthorName($data['author_name'] ?? ''),
            new CommitAuthorEmail($data['author_email'] ?? ''),
            new CommitAuthoredDate($data['authored_date'] ?? ''),
            new CommitCommitterName($data['committer_name'] ?? ''),
            new CommitCommitterEmail($data['committer_email'] ?? ''),
            new CommitCommittedDate($data['committed_date'] ?? ''),
        );
    }
}
