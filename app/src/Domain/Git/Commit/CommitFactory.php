<?php

declare(strict_types=1);

namespace App\Domain\Git\Commit;

use App\Domain\Git\Commit\ValueObject\CommitAuthorDate;
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
            $this->parseAuthorName($data),
            $this->parseAuthorEmail($data),
            $this->parseCommitAuthorDate($data),
            $this->parseCommitStats($data),
        );
    }

    public function parseCommitId(string $data): CommitId
    {
        $result = [];
        preg_match('/(?P<commit>\{\|p\|}commit: (?P<value>[a-f0-9]{32}))/', $data, $result);
        return new CommitId($result['value'] ?? '');
    }

    public function parseTextProperty(string $propertyName, string $data): string
    {
        $result = [];
        preg_match('/(?P<property>\{\|p\|}' . $propertyName . ': (?P<value>.+?(?={)))/', $data, $result);
        return trim($result['value'] ?? '');
    }

    public function parseAuthorEmail(string $data): CommitAuthorEmail
    {
        $value = $this->parseTextProperty('email', $data);
        return new CommitAuthorEmail($value);
    }

    public function parseAuthorName(string $data): CommitAuthorName
    {
        $value = $this->parseTextProperty('name', $data);
        return new CommitAuthorName($value);
    }

    public function parseCommitAuthorDate(string $data): CommitAuthorDate
    {
        $result = [];
        preg_match('/(?P<date>\{\|p\|}date: (?P<value>.+?(?={)))/', $data, $result);
        return new CommitAuthorDate($result['value'] ?? '', DATE_ISO8601_EXPANDED);
    }

    public function parseCommitStats(string $date): CommitStats
    {
        $result = [];
        preg_match(
            '/\{\|p\|}stat:( +(?P<files>\d+) files? changed)?(,? +(?P<insertions>\d+).+\(\+\))?(,? +(?P<deletions>\d+).+\(\-\))?/',
            $date,
            $result,
        );

        return new CommitStats([
            'files' => (int) ($result['files'] ?? 0),
            'additions' => (int) ($result['insertions'] ?? 0),
            'deletions' => (int) ($result['deletions'] ?? 0),
        ]);
    }
}
