<?php

namespace App\Domain\Git\Commit;

use App\Domain\Git\Commit\ValueObject\CommitAuthorDate;
use App\Domain\Git\Commit\ValueObject\CommitAuthorEmail;
use App\Domain\Git\Commit\ValueObject\CommitAuthorName;
use App\Domain\Git\Commit\ValueObject\CommitId;
use App\Domain\Git\Commit\ValueObject\CommitStats;

final readonly class Commit
{
    public function __construct(
        public CommitId $id,
        public CommitAuthorName $authorName,
        public CommitAuthorEmail $authorEmail,
        public CommitAuthorDate $authorDate,
        public CommitStats $stats,
    ) {
    }
}
