<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\Commit;

use App\Domain\Common\EntityInterface;
use App\Domain\Gitlab\Commit\ValueObject\CommitAdditions;
use App\Domain\Gitlab\Commit\ValueObject\CommitAuthorDate;
use App\Domain\Gitlab\Commit\ValueObject\CommitAuthorEmail;
use App\Domain\Gitlab\Commit\ValueObject\CommitDeletions;
use App\Domain\Gitlab\Commit\ValueObject\CommitFiles;
use App\Domain\Gitlab\Commit\ValueObject\CommitGitCommitId;
use App\Domain\Gitlab\Commit\ValueObject\CommitProjectId;

final readonly class Commit implements EntityInterface
{
    public function __construct(
        public CommitGitCommitId $gitCommitId,
        public CommitProjectId $projectId,
        public CommitAuthorEmail $authorEmail,
        public CommitAuthorDate $authorDate,
        public CommitFiles $files,
        public CommitAdditions $additions,
        public CommitDeletions $deletions,
    ) {
    }
}
