<?php

namespace App\Domain\Gitlab\Commit;

use App\Domain\Common\AbstractEntity;
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

final readonly class Commit extends AbstractEntity
{
    public function __construct(
        public CommitId $id,
        public CommitProjectId $projectId,
        public CommitGitCommitId $gitCommitId,
        public CommitTitle $title,
        public CommitCreatedAt $createdAt,
        public CommitWebUrl $webUrl,
        public CommitAuthorName $authorName,
        public CommitAuthorEmail $authorEmail,
        public CommitAuthoredDate $authoredDate,
        public CommitCommitterName $committerName,
        public CommitCommitterEmail $committerEmail,
        public CommitCommittedDate $committedDate,
    ) {
    }
}
