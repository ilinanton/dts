<?php

namespace App\Domain\Git\Commit;

use App\Domain\Git\Commit\ValueObject\CommitAuthoredDate;
use App\Domain\Git\Commit\ValueObject\CommitAuthorEmail;
use App\Domain\Git\Commit\ValueObject\CommitAuthorName;
use App\Domain\Git\Commit\ValueObject\CommitId;
use App\Domain\Git\Commit\ValueObject\CommitStats;

final readonly class CommitFactory
{
    public function create(string $data): Commit
    {
        return new Commit(
            $this->parseCommitId($data),
            new CommitAuthorName(),
            new CommitAuthorEmail(),
            $this->parseCommitAuthoredDate($data),
            new CommitStats(),
        );
    }

    public function parseCommitId(string $data): CommitId
    {
        $result = [];
        preg_match('/(?P<commit>\{\|p\|}commit: (?P<value>[a-f0-9]{32}))/', $data, $result);
        return new CommitId($result['value'] ?? '');
    }

    public function parseCommitAuthoredDate(string $data): CommitAuthoredDate
    {
        $result = [];
        preg_match('/(?P<date>\{\|p\|}date: (?P<value>.+?(?={)))/', $data, $result);
        return new CommitAuthoredDate($result['value'] ?? '', DATE_ISO8601_EXPANDED);
    }
}
