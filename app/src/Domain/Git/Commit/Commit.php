<?php

namespace App\Domain\Git\Commit;

use App\Domain\Git\Commit\ValueObject\CommitId;

final readonly class Commit
{
    public function __construct(
        public CommitId $commitId
    ) {
    }
}
