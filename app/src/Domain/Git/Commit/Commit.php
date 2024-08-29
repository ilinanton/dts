<?php

namespace App\Domain\Git\Commit;

use App\Domain\Git\Commit\ValueObject\CommitAuthoredDate;
use App\Domain\Git\Commit\ValueObject\CommitAuthorEmail;
use App\Domain\Git\Commit\ValueObject\CommitAuthorName;
use App\Domain\Git\Commit\ValueObject\CommitId;
use App\Domain\Git\Commit\ValueObject\CommitStats;
use App\Domain\Git\Commit\ValueObject\ProjectId;

final readonly class Commit
{
    public function __construct(
        public CommitId $id,
        public ProjectId $projectId,
        public CommitAuthorName $authorName,
        public CommitAuthorEmail $authorEmail,
        public CommitAuthoredDate $authoredDate,
        public CommitStats $stats,
    ) {
    }
}
