<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\Commit;

use App\Domain\Gitlab\Commit\ValueObject\CommitAdditions;
use App\Domain\Gitlab\Commit\ValueObject\CommitAuthorDate;
use App\Domain\Gitlab\Commit\ValueObject\CommitAuthorEmail;
use App\Domain\Gitlab\Commit\ValueObject\CommitDeletions;
use App\Domain\Gitlab\Commit\ValueObject\CommitFiles;
use App\Domain\Gitlab\Commit\ValueObject\CommitGitCommitId;
use App\Domain\Gitlab\Commit\ValueObject\CommitProjectId;

final readonly class CommitFactory
{
    public function create(
        int $projectId,
        string $gitCommitId,
        string $authorEmail,
        string $authorDate,
        int $files,
        int $additions,
        int $deletions,
    ): Commit {
        return new Commit(
            new CommitGitCommitId($gitCommitId),
            new CommitProjectId($projectId),
            new CommitAuthorEmail($authorEmail),
            new CommitAuthorDate($authorDate),
            new CommitFiles($files),
            new CommitAdditions($additions),
            new CommitDeletions($deletions),
        );
    }
}
